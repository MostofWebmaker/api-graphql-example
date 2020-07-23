<?php

namespace App\Model\Features\Advertisement\Advertisement;

use App\Exceptions\GraphQLException;
use App\Model\Advertisement\Command\Advertisement\AdvertisementStatusChangeRequestCommand;
use App\Model\Advertisement\Entity\AdvertisementStatus;
use App\Model\Advertisement\Entity\AdvertisementStatusType;
use App\Model\Advertisement\Repository\AdvertisementRepository;
use App\Model\Advertisement\Repository\AdvertisementStatusRepository;
use App\Model\Advertisement\Repository\AdvertisementStatusTypeRepository;
use App\Model\Flusher;
use App\Model\User\Entity\User;
use App\Model\Advertisement\Entity\Advertisement;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Symfony\Component\Security\Core\Security;

class AdvertisementStatusChangeFeature
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
     * @var AdvertisementStatusRepository
     */
    private AdvertisementStatusRepository $advertisementStatusRepository;
    /**
     * @var AdvertisementStatusTypeRepository
     */
    private AdvertisementStatusTypeRepository $advertisementStatusTypeRepository;

    /**
     * @var Security
     */
    private Security $security;

    /**
     * AdvertisementStatusChangeFeature constructor.
     * @param LoggerInterface $logger
     * @param Flusher $flusher
     * @param AdvertisementRepository $advertisementRepository
     * @param AdvertisementStatusRepository $advertisementStatusRepository
     * @param AdvertisementStatusTypeRepository $advertisementStatusTypeRepository
     * @param Security $security
     */
    public function __construct(
        LoggerInterface $logger,
		Flusher $flusher,
        AdvertisementRepository $advertisementRepository,
        AdvertisementStatusRepository $advertisementStatusRepository,
        AdvertisementStatusTypeRepository $advertisementStatusTypeRepository,
        Security $security
    )
    {
        $this->logger = $logger;
	    $this->flusher = $flusher;
	    $this->advertisementRepository = $advertisementRepository;
	    $this->advertisementStatusRepository = $advertisementStatusRepository;
	    $this->advertisementStatusTypeRepository = $advertisementStatusTypeRepository;
	    $this->security = $security;
    }

    /**
     * @param AdvertisementStatusChangeRequestCommand $command
     * @param User $user
     * @return AdvertisementStatus|null
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function changeStatus (AdvertisementStatusChangeRequestCommand $command, User $user): ?AdvertisementStatus
    {
        if (!$user->getUserStatus()->isActive()) {
            throw new \RuntimeException('Ваш пользовательский аккаунт еще не активирован!');
        }
        //проверка на корректность перевода статуса обьявления
        if (!in_array($command->getStatusId(), array_keys(AdvertisementStatusType::ADVERTISEMENT_USER_STATUS_LIST))) {
            throw new \RuntimeException("Доступ запрещен!");
        }
    	$this->flusher->beginTransaction();
	    try {
            $userAdvertisementIds = $this->advertisementRepository->getAdvertisementIdsByUserId($user->getId());
            if (!$userAdvertisementIds) {
                throw new \RuntimeException("Ошибка! Пользователь {$user->getName()->getFIO()} не имеет ни одного объявления.");
            }
            /**
             * @var Advertisement $advertisement
             */
            if (!$advertisement = $this->advertisementRepository->find($command->getAdvertisementId())) {
                throw new \RuntimeException("Данного объявления с id #{$command->getAdvertisementId()} не существует!");
            }
            if (!in_array($advertisement->getId(), $userAdvertisementIds)) {
                throw new \RuntimeException("Доступ запрещен! Пользователь {$user->getName()->getFIO()} имеет право обновлять только свои объявления!");
            }
            /**
             * @var AdvertisementStatus $advertisementStatus
             */
            if (!$advertisementStatus = $this->advertisementStatusRepository->find($advertisement->getStatus())) {
                throw new \RuntimeException("Cущности статуса для объявления c id #{$advertisement->getId()} не существует!");
            }
            /**
             * @var AdvertisementStatusType $advertisementStatusType
             */
            if (!$advertisementStatusType = $this->advertisementStatusTypeRepository->find($command->getStatusId())) {
                throw new \RuntimeException('Объект UseStatusType не найден!');
            }
            $advertisementStatus->setAdvertisementStatusType($advertisementStatusType);
            $advertisementStatus->setDateUpdated(new \DateTimeImmutable());
            //затираем старое сообщение админа
            if ($advertisementStatus->getMessage()) {
                $advertisementStatus->setMessage(null);
            }

            $this->flusher->persist($advertisementStatus);
            $this->flusher->flush();

            //уведомление для админа
		    $this->flusher->commit();

	    } catch (RuntimeException $exception) {
		    $this->logger->critical('Сообщение об ошибке #'.$exception->getMessage().' Обновление статуса объявления завершилось неудачей!');
		    $this->flusher->rollback();
           // throw GraphQLException::fromString($exception->getMessage());
            throw new \RuntimeException($exception->getMessage());
	    }
        return $advertisementStatus ?? null;
    }
}
