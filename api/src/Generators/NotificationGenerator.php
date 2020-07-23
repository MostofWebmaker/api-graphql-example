<?php

namespace App\Generators;

use App\Dto\CustomPushMessageDto;
use App\Model\Advertisement\Entity\Advertisement;
use App\Model\Advertisement\Entity\AdvertisementStatus;
use App\Model\Flusher;
use App\Model\Notifications\Entity\EventType;
use App\Model\Notifications\Entity\Notifications;
use App\Model\User\Entity\Sex;
use App\Model\User\Entity\User;
use App\Model\User\Entity\UserStatus;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

/**
 * Class NotificationGenerator
 * @package App\Generators
 */
class NotificationGenerator
{
    /**
     * @var User
     */
    private ?User $user = null;
    /**
     * @var EventType
     */
    private ?EventType $eventType = null;
    /**
     * @var Flusher
     */
    private Flusher $flusher;

    /**
     * @var UserStatus|null
     */
    private ?UserStatus $userStatus = null;
    /**
     * @var AdvertisementStatus|null
     */
    private ?AdvertisementStatus $advertisementStatus = null;
    /**
     * @var Advertisement|null
     */
    private ?Advertisement $advertisement = null;
    /**
     * @var CustomPushMessageDto|null
     */
    private ?CustomPushMessageDto $customMessage = null;

    private NotificationMessage $notificationMessage;

    /**
     * NotificationGenerator constructor.
     * @param Flusher $flusher
     * @param NotificationMessage $notificationMessage
     */
    public function __construct(Flusher $flusher, NotificationMessage $notificationMessage)
    {
        $this->flusher = $flusher;
        $this->notificationMessage = $notificationMessage;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     */
    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return EventType|null
     */
    public function getEventType(): ?EventType
    {
        return $this->eventType;
    }

    /**
     * @param EventType|null $eventType
     */
    public function setEventType(?EventType $eventType): void
    {
        $this->eventType = $eventType;
    }

    /**
     * @return Flusher
     */
    public function getFlusher(): Flusher
    {
        return $this->flusher;
    }

    /**
     * @param Flusher $flusher
     */
    public function setFlusher(Flusher $flusher): void
    {
        $this->flusher = $flusher;
    }

    /**
     * @return UserStatus|null
     */
    public function getUserStatus(): ?UserStatus
    {
        return $this->userStatus;
    }

    /**
     * @param UserStatus|null $userStatus
     */
    public function setUserStatus(?UserStatus $userStatus): void
    {
        $this->userStatus = $userStatus;
    }

    /**
     * @return AdvertisementStatus|null
     */
    public function getAdvertisementStatus(): ?AdvertisementStatus
    {
        return $this->advertisementStatus;
    }

    /**
     * @param AdvertisementStatus|null $advertisementStatus
     */
    public function setAdvertisementStatus(?AdvertisementStatus $advertisementStatus): void
    {
        $this->advertisementStatus = $advertisementStatus;
    }

    /**
     * @return Advertisement|null
     */
    public function getAdvertisement(): ?Advertisement
    {
        return $this->advertisement;
    }

    /**
     * @param Advertisement|null $advertisement
     */
    public function setAdvertisement(?Advertisement $advertisement): void
    {
        $this->advertisement = $advertisement;
    }

    /**
     * @return CustomPushMessageDto|null
     */
    public function getCustomMessage(): ?CustomPushMessageDto
    {
        return $this->customMessage;
    }

    /**
     * @param CustomPushMessageDto|null $customMessage
     */
    public function setCustomMessage(?CustomPushMessageDto $customMessage): void
    {
        $this->customMessage = $customMessage;
    }

    /**
     * @return NotificationMessage|null
     */
    public function getNotificationMessage(): ?NotificationMessage
    {
        return $this->notificationMessage;
    }

    /**
     * @param NotificationMessage|null $notificationMessage
     */
    public function setNotificationMessage(?NotificationMessage $notificationMessage): void
    {
        $this->notificationMessage = $notificationMessage;
    }

    /**
     * @return NotificationMessage
     */
    private function generateNotificationMessage(): ?NotificationMessage
    {
        if ($this->getCustomMessage() && $this->getCustomMessage()->getTitle() && $this->getCustomMessage()->getBody()) {
            $this->notificationMessage->build($this->getCustomMessage()->getTitle(), $this->getCustomMessage()->getBody());
            if (!$this->notificationMessage->isBuild()) {
                throw new \RuntimeException('Сборка обьекта NotificationMessage завершилась ошибкой!');
            }
            return $this->getNotificationMessage();
        }
        $defaultFirstBodyText = 'Уважаемый пользователь, ';
        $firstBodyText = $this->getUser() ? (($this->getUser()->getSex()->getSex() ? ($this->getUser()->getSex()->getSex() === Sex::MALE ? 'Уважаемый, ' : 'Уважаемая, ') : $defaultFirstBodyText)) : $defaultFirstBodyText;
        switch ($this->eventType->getId()) {
            case 1:
                if (!$this->getUserStatus()) {
                    throw new \RuntimeException('Отсутcтвует сущность статуса пользователя!');
                }
                if (!$titleEventText = $this->eventType->getName()) {
                    throw new \RuntimeException('Отсутcтвует наименование типа события!');
                }
                $resultTitleText = $this->getCustomMessage() ? ($this->getCustomMessage()->getTitle() ? $this->getCustomMessage()->getTitle() : $titleEventText) : $titleEventText;
                $bodyText = "$firstBodyText{$this->user->getName()->getFIO()}! Ваш пользовательский аккаунт переведен в статус '{$this->getUserStatus()->getUserStatusType()->getDescription()}'. Обновления в приложении увидите через несколько минут.";
                $resultBodyText = $this->getCustomMessage() ? ($this->getCustomMessage()->getBody() ? $this->getCustomMessage()->getBody() : $bodyText) : $bodyText;
                $this->notificationMessage->build($resultTitleText, $resultBodyText);
                if (!$this->notificationMessage->isBuild()) {
                    throw new \RuntimeException('Сборка обьекта NotificationMessage завершилась ошибкой!');
                }
                return $this->getNotificationMessage();
            case 2:
                if (!$this->getAdvertisementStatus() && !$this->getAdvertisement()) {
                    throw new \RuntimeException('Отсутcтвует сущность обьявления и(или) статуса объявления!');
                }
                if (!$titleEventText = $this->eventType->getName()) {
                    throw new \RuntimeException('Отсутcтвует наименование типа события!');
                }
                $resultTitleText = $this->getCustomMessage() ? ($this->getCustomMessage()->getTitle() ? $this->getCustomMessage()->getTitle(): $titleEventText) : $titleEventText;
                $bodyText = "$firstBodyText{$this->user->getName()->getFIO()}! Ваше объявлениe №{$this->getAdvertisement()->getId()} переведено в статус '{$this->getAdvertisementStatus()->getAdvertisementStatusType()->getDescription()}'. Обновления в приложении увидите через несколько минут.";
                $resultBodyText = $this->getCustomMessage() ? ($this->getCustomMessage()->getBody() ? $this->getCustomMessage()->getBody() : $bodyText ) : $bodyText;
                //return new NotificationMessage($resultTitleText, $resultBodyText);
                $this->notificationMessage->build($resultTitleText, $resultBodyText);
                if (!$this->notificationMessage->isBuild()) {
                    throw new \RuntimeException('Сборка обьекта NotificationMessage завершилась ошибкой!');
                }
                return $this->getNotificationMessage();
                break;
            default:
                if (!$titleEventText = $this->eventType->getName()) {
                    throw new \RuntimeException('Отсутcтвует наименование типа события!');
                }
                $resultTitleText = $this->getCustomMessage() ? ($this->getCustomMessage()->getTitle() ? $this->getCustomMessage()->getTitle() : $titleEventText) :$titleEventText;
                $resultBodyText = $this->getCustomMessage()  ? ($this->getCustomMessage()->getBody() ? $this->getCustomMessage()->getBody() : $this->getEventType()->getDescription()) : $this->getEventType()->getDescription();
                $this->notificationMessage->build($resultTitleText, $resultBodyText);
                if (!$this->notificationMessage->isBuild()) {
                    throw new \RuntimeException('Сборка обьекта NotificationMessage завершилась ошибкой!');
                }
                return $this->getNotificationMessage();

        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function generateNotification() {
        if (!$notificationMessage = $this->generateNotificationMessage()) {
            throw new \RuntimeException('Ошибка при создании обьекта NotificationMessage');
        }
        $notification = new Notifications($this->getUser(), $this->getEventType(), $notificationMessage->getTitle(), $notificationMessage->getBody());
        $this->flusher->persist($notification);
        $this->flusher->flush();
    }
}
