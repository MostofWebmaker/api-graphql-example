<?php

namespace App\Model\Features\Advertisement\Advertisement;

use App\Exceptions\GraphQLException;
use App\Generators\PhotoGenerator;
use App\Model\Advertisement\Command\Advertisement\CreateAdvertisementRequestCommand;
use App\Model\Advertisement\Entity\Address;
use App\Model\Advertisement\Entity\AdvertisementStatus;
use App\Model\Advertisement\Entity\AdvertisementStatusType;
use App\Model\Advertisement\Entity\BodyAdvertisement;
use App\Model\Advertisement\Entity\CategoryAdvertisement;
use App\Model\Advertisement\Entity\City;
use App\Model\Advertisement\Entity\Country;
use App\Model\Advertisement\Entity\District;
use App\Model\Advertisement\Entity\House;
use App\Model\Advertisement\Entity\Street;
use App\Model\Advertisement\Entity\SubwayStation;
use App\Model\Advertisement\Repository\AddressRepository;
use App\Model\Advertisement\Repository\AdvertisementStatusTypeRepository;
use App\Model\Advertisement\Repository\CategoryAdvertisementRepository;
use App\Model\Advertisement\Repository\CityRepository;
use App\Model\Advertisement\Repository\CountryRepository;
use App\Model\Advertisement\Repository\DistrictRepository;
use App\Model\Advertisement\Repository\HouseRepository;
use App\Model\Advertisement\Repository\StreetRepository;
use App\Model\Advertisement\Repository\SubwayStationRepository;
use App\Model\Flusher;
use App\Model\User\Entity\User;
use App\Model\Advertisement\Entity\Advertisement;
use App\Service\FileSaver;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Psr\Log\LoggerInterface;
use RuntimeException;

/**
 * Class AdvertisementCreateFeature
 * @package App\Model\Features\Advertisement\Advertisement
 */
class AdvertisementCreateFeature
{
    /** @var LoggerInterface $logger */
	private LoggerInterface $logger;
	/**
	 * @var Flusher
	 */
	private Flusher $flusher;
    /**
     * @var CategoryAdvertisementRepository
     */
	private CategoryAdvertisementRepository $categoryAdvertisementRepository;

    /**
     * @var AddressRepository
     */
    private AddressRepository $addressRepository;

    /**
     * @var CountryRepository
     */
    private CountryRepository $countryRepository;

    /**
     * @var CityRepository
     */
    private CityRepository $cityRepository;

    /**
     * @var DistrictRepository
     */
    private DistrictRepository $districtRepository;

    /**
     * @var StreetRepository
     */
    private StreetRepository $streetRepository;

    /**
     * @var HouseRepository
     */
    private HouseRepository $houseRepository;

    /**
     * @var SubwayStationRepository
     */
    private SubwayStationRepository $subwayStationRepository;

    /**
     * @var AdvertisementStatusTypeRepository
     */
    private AdvertisementStatusTypeRepository $advertisementStatusTypeRepository;
    /**
     * @var PhotoGenerator
     */
    private ?PhotoGenerator $photoGenerator = null;

    //private FileSaver $fileSaver;

    /**
     * AdvertisementCreateFeature constructor.
     * @param LoggerInterface $logger
     * @param Flusher $flusher
     * @param CategoryAdvertisementRepository $categoryAdvertisementRepository
     * @param AddressRepository $addressRepository
     * @param CountryRepository $countryRepository
     * @param CityRepository $cityRepository
     * @param DistrictRepository $districtRepository
     * @param StreetRepository $streetRepository
     * @param HouseRepository $houseRepository
     * @param SubwayStationRepository $subwayStationRepository
     * @param AdvertisementStatusTypeRepository $advertisementStatusTypeRepository
     * @param PhotoGenerator $photoGenerator
     */
    public function __construct(
        LoggerInterface $logger,
		Flusher $flusher,
        CategoryAdvertisementRepository $categoryAdvertisementRepository,
        AddressRepository $addressRepository,
        CountryRepository $countryRepository,
        CityRepository $cityRepository,
        DistrictRepository $districtRepository,
        StreetRepository $streetRepository,
        HouseRepository $houseRepository,
        SubwayStationRepository $subwayStationRepository,
        AdvertisementStatusTypeRepository $advertisementStatusTypeRepository,
        PhotoGenerator $photoGenerator
    )
    {
        $this->logger = $logger;
	    $this->flusher = $flusher;
	    $this->categoryAdvertisementRepository = $categoryAdvertisementRepository;
	    $this->addressRepository = $addressRepository;
	    $this->countryRepository = $countryRepository;
	    $this->cityRepository = $cityRepository;
	    $this->districtRepository = $districtRepository;
	    $this->streetRepository = $streetRepository;
	    $this->houseRepository = $houseRepository;
	    $this->subwayStationRepository = $subwayStationRepository;
	    $this->advertisementStatusTypeRepository = $advertisementStatusTypeRepository;
	    $this->photoGenerator = $photoGenerator;
    }

    /**
     * @param CreateAdvertisementRequestCommand $command
     * @param User $user
     * @return Advertisement|null
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function createAdvertisement(CreateAdvertisementRequestCommand $command, User $user): ?Advertisement
    {
        if (!$user->getUserStatus()->isActive()) {
            throw new \RuntimeException('Ваш пользовательский аккаунт еще не активирован!');
        }
    	$this->flusher->beginTransaction();
	    try {
	        //создать все сущности объявления
            $categoryAdvertisementId = $command->getCategoryAdvertisementId(); // достаточно ID категории
            /** @var CategoryAdvertisement $categoryAdvertisement */
            $categoryAdvertisement = $this->categoryAdvertisementRepository->find($categoryAdvertisementId);
            if (!$categoryAdvertisement) {
                throw new \RuntimeException('Данной категории объявлений не существует!');
            }

	        if (!$bodyAdvertisement = new BodyAdvertisement($command->getBodyAdvertisement()->getTitle(), $command->getBodyAdvertisement()->getDescription())) {
	            throw new \RuntimeException('Системная ошибка получения обьекта BodyAdvertisement');
            }

	        if ($price = $command->getBodyAdvertisement()->getPrice()) {
	            $bodyAdvertisement->setPrice($price);
            }
	        $country = $this->countryRepository->getCountryByName($command->getAddress()->getCountry()) ? $this->countryRepository->getCountryByName($command->getAddress()->getCountry()) : new Country($command->getAddress()->getCountry());
	        $city = $this->cityRepository->getCityByName($command->getAddress()->getCity()) ? $this->cityRepository->getCityByName($command->getAddress()->getCity()) : new City($command->getAddress()->getCity());
	        $district = $this->districtRepository->getDistrictByName($command->getAddress()->getDistrict()) ? $this->districtRepository->getDistrictByName($command->getAddress()->getDistrict()) : new District($command->getAddress()->getDistrict());

	        $street = $command->getAddress()->getStreet() ? ($this->streetRepository->getStreetByName($command->getAddress()->getStreet()) ? $this->streetRepository->getStreetByName($command->getAddress()->getStreet()) : new Street($command->getAddress()->getStreet())) : null;
	        $house = $command->getAddress()->getHouse() ? ($this->houseRepository->getHouseByNumber($command->getAddress()->getHouse()) ? $this->houseRepository->getHouseByNumber($command->getAddress()->getHouse()) : new House($command->getAddress()->getHouse())) : null;

            $currentAddress = $this->addressRepository->getIdentityAddress($country->getTitle(), $city->getTitle(), $district->getTitle(), $street ? $street->getTitle() : null , $house ? $house->getTitle() : null);
            if (!$currentAddress) {
                if (!$address = new Address($country, $city, $district)) {
                    throw new \RuntimeException('Системная ошибка получения обьекта Address');
                }
            } else {
                $address = $currentAddress;
            }

            if ($street) {
                $address->setStreet($street);
                $this->flusher->persist($street);
            }
            if ($house) {
                $address->setHouse($house);
                $this->flusher->persist($house);
            }
            $this->flusher->persist($address);

            /** @var SubwayStation $subwayStation */
            $subwayStation = $command->getSubwayStation() ? ($this->subwayStationRepository->getSubwayStationByName($command->getSubwayStation()) ? $this->subwayStationRepository->getSubwayStationByName($command->getSubwayStation()) : new SubwayStation($command->getSubwayStation())) : null;

            /** @var AdvertisementStatusType $advertisementDraftStatusType */
            if (!$advertisementDraftStatusType = $this->advertisementStatusTypeRepository->find(AdvertisementStatusType::STATUS_DRAFT)) {
                throw new \RuntimeException('Status DRAFT is missing!');
            }
            /** @var AdvertisementStatus $advertisementStatus */
            $advertisementStatus = new AdvertisementStatus($advertisementDraftStatusType);

            // создать само объявление
            $advertisement = new Advertisement($categoryAdvertisement, $bodyAdvertisement, $address, $user, $advertisementStatus);
            if ($subwayStation) {
                $advertisement->setSubwayStation($subwayStation);
            }
            if ($command->getPhotos()) {
                //генерация и сохранение фотографий
                if (!$this->photoGenerator->generate($advertisement, $command->getPhotos())) {
                    throw new \RuntimeException('Генерация и сохранение фотографий завершилось ошибкой!');
                }
            }

            $this->flusher->persist($bodyAdvertisement);
            $this->flusher->persist($country);
            $this->flusher->persist($city);
            $this->flusher->persist($district);
            $this->flusher->persist($advertisementStatus);
            $this->flusher->persist($advertisement);
            $this->flusher->flush();
            //уведомление для админа
		    $this->flusher->commit();
	    } catch (\RuntimeException $exception) {
		    $this->logger->critical('Сообщение об ошибке #'.$exception->getMessage().' Создание объявления завершилось неудачей!');
		    $this->flusher->rollback();
            throw new \RuntimeException($exception->getMessage());
	    }
        return $advertisement ?? null;
    }
}
