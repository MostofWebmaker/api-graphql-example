<?php

namespace App\Model\Features\Advertisement\Advertisement;

use App\Exceptions\GraphQLException;
use App\Generators\PhotoGenerator;
use App\Model\Advertisement\Command\Advertisement\DeleteAdvertisementRequestCommand;
use App\Model\Advertisement\Entity\Address;
use App\Model\Advertisement\Entity\AdvertisementStatus;
use App\Model\Advertisement\Entity\BodyAdvertisement;
use App\Model\Advertisement\Repository\AdvertisementRepository;
use App\Model\Flusher;
use App\Model\User\Entity\User;
use App\Model\Advertisement\Entity\Advertisement;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Psr\Log\LoggerInterface;
use RuntimeException;

class AdvertisementDeleteFeature
{
    /** @var LoggerInterface $logger */
	private LoggerInterface $logger;
	/**
	 * @var Flusher
	 */
	private Flusher $flusher;
    /**
     * @var AdvertisementRepository
     */
    private AdvertisementRepository $advertisementRepository;

    /**
     * @var PhotoGenerator
     */
    private ?PhotoGenerator $photoGenerator = null;

    /**
     * AdvertisementUpdateFeature constructor.
     * @param LoggerInterface $logger
     * @param Flusher $flusher
     * @param AdvertisementRepository $advertisementRepository
     * @param PhotoGenerator $photoGenerator
     */
    public function __construct(
        LoggerInterface $logger,
		Flusher $flusher,
        AdvertisementRepository $advertisementRepository,
        PhotoGenerator $photoGenerator
    )
    {
        $this->logger = $logger;
	    $this->flusher = $flusher;
	    $this->advertisementRepository = $advertisementRepository;
	    $this->photoGenerator = $photoGenerator;
    }

    /**
     * @param DeleteAdvertisementRequestCommand $command
     * @param User $user
     * @return void
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function deleteAdvertisement(DeleteAdvertisementRequestCommand $command, User $user)
    {
        if (!$user->getUserStatus()->isActive()) {
            throw new \RuntimeException('Ваш пользовательский аккаунт еще не активирован!');
        }
    	$this->flusher->beginTransaction();
	    try {
            /**
             * @var Advertisement $advertisement
             */
            $advertisement = $this->advertisementRepository->find($command->getId());
            if (!$advertisement) {
                throw new \RuntimeException('Данного объявления не существует!');
            }

            /**
             * @var BodyAdvertisement $bodyAdvertisement
             */
            if (!$bodyAdvertisement = $advertisement->getBodyAdvertisement()) {
                throw new \RuntimeException("Не существует тела для объявления с id #{$advertisement->getId()}");
            }

            /**
             * @var Address $address
             */
            if (!$address =  $advertisement->getAddress()) {
                throw new \RuntimeException("Не существует адреса для объявления с id #{$advertisement->getId()}");
            }

            /**
             * @var AdvertisementStatus $advertisementStatus
             */
            if (!$advertisementStatus =  $advertisement->getStatus()) {
                throw new \RuntimeException("Не существует статуса для объявления с id #{$advertisement->getId()}");
            }
            if ($messages = $advertisement->getMessages()) {
                foreach ($messages as $message) {
                    $this->flusher->remove($message);
                }
            }
            
            $this->photoGenerator->delete($advertisement);

            $this->flusher->remove($advertisement);
            $this->flusher->remove($advertisementStatus);
            $this->flusher->remove($bodyAdvertisement);
            $this->flusher->remove($address);

            $this->flusher->flush();

            //уведомление для админа
		    $this->flusher->commit();
	    } catch (RuntimeException $exception) {
		    $this->logger->critical('Сообщение об ошибке #'.$exception->getMessage().' Удаление объявления завершилось неудачей!');
		    $this->flusher->rollback();
            throw new \RuntimeException($exception->getMessage());
	    }
    }
}
