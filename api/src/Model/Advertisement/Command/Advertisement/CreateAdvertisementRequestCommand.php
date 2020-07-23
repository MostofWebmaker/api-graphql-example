<?php
declare(strict_types=1);

namespace App\Model\Advertisement\Command\Advertisement;

use App\Model\Advertisement\Entity\Address;
use App\Model\Advertisement\Entity\BodyAdvertisement;
use App\Model\Advertisement\Entity\SubwayStation;
use App\Model\User\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Validator\Constraints as Assert;

class CreateAdvertisementRequestCommand
{
    /**
     * @var int
     * @Assert\Positive()
     * @Assert\NotBlank()
     */
    public int $categoryAdvertisementId = 1; // @Assert\NotBlank()

    /**
     * @var CreateBodyAdvertisementRequestCommand
     * @Assert\NotBlank()
     */
    public ?CreateBodyAdvertisementRequestCommand $bodyAdvertisement = null;

    /**
     * @var CreateAddressRequestCommand
     * @Assert\NotBlank()
     */
    public ?CreateAddressRequestCommand $address = null;

    /**
     * @var string
     */
    public ?string $subwayStation = null;
    /**
     * @var array|null
     */
    public ?array $photos = [];

    /**
     * @return CreateBodyAdvertisementRequestCommand|null
     */
    public function getBodyAdvertisement(): ?CreateBodyAdvertisementRequestCommand
    {
        return $this->bodyAdvertisement;
    }

    /**
     * @param CreateBodyAdvertisementRequestCommand $bodyAdvertisement
     */
    public function setBodyAdvertisement(CreateBodyAdvertisementRequestCommand $bodyAdvertisement): void
    {
        $this->bodyAdvertisement = $bodyAdvertisement;
    }

    /**
     * @return CreateAddressRequestCommand|null
     */
    public function getAddress(): ?CreateAddressRequestCommand
    {
        return $this->address;
    }

    /**
     * @param CreateAddressRequestCommand $address
     */
    public function setAddress(CreateAddressRequestCommand $address): void
    {
        $this->address = $address;
    }

    /**
     * @return int
     */
    public function getCategoryAdvertisementId(): int
    {
        return $this->categoryAdvertisementId;
    }

    /**
     * @param int $categoryAdvertisementId
     */
    public function setCategoryAdvertisementId(int $categoryAdvertisementId): void
    {
        $this->categoryAdvertisementId = $categoryAdvertisementId;
    }

    /**
     * @return string|null
     */
    public function getSubwayStation(): ?string
    {
        return $this->subwayStation;
    }

    /**
     * @param string $subwayStation
     */
    public function setSubwayStation(string $subwayStation): void
    {
        $this->subwayStation = $subwayStation;
    }

    /**
     * @return array|null
     */
    public function getPhotos(): ?array
    {
        return $this->photos;
    }

    /**
     * @param array|null $photos
     */
    public function setPhotos(?array $photos): void
    {
        $this->photos = $photos;
    }
}