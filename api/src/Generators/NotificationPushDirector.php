<?php

namespace App\Generators;

use App\Model\Advertisement\Entity\Advertisement;
use App\Model\Advertisement\Entity\AdvertisementStatus;
use App\Model\Notifications\Entity\EventType;
use App\Model\User\Entity\User;
use App\Model\User\Entity\UserStatus;
use App\Model\User\Repository\SessionRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

/**
 * Class NotificationPushDirector
 * @package App\Generators
 */
class NotificationPushDirector
{
    /**
     * @var EventType
     */
    private EventType $eventType;
    /**
     * @var User
     */
    private User $user;
    /**
     * @var NotificationGenerator
     */
    private NotificationGenerator $notificationGenerator;
    /**
     * @var FCMMessage
     */
    private FCMMessage $fcmMessage;
    /**
     * @var PushGenerator
     */
    private PushGenerator $pushGenerator;
    /**
     * @var Advertisement|null
     */
    private ?Advertisement $advertisement = null;
    /**
     * @var AdvertisementStatus|null
     */
    private ?AdvertisementStatus $advertisementStatus = null;
    /**
     * @var UserStatus|null
     */
    private ?UserStatus $userStatus = null;
    /**
     * @var SessionGenerator
     */
    private SessionGenerator $sessionGenerator;

    /**
     * @param EventType $eventType
     * @param User $user
     * @param NotificationGenerator $notificationGenerator
     * @param FCMMessage $fcmMessage
     * @param PushGenerator $pushGenerator
     * @param SessionGenerator $sessionGenerator
     */
    public function build
    (
        EventType $eventType,
        User $user,
        NotificationGenerator $notificationGenerator,
        FCMMessage $fcmMessage,
        PushGenerator $pushGenerator,
        SessionGenerator $sessionGenerator
    ) {
        $this->eventType = $eventType;
        $this->user = $user;
        $this->notificationGenerator = $notificationGenerator;
        $this->fcmMessage = $fcmMessage;
        $this->pushGenerator = $pushGenerator;
        $this->sessionGenerator = $sessionGenerator;
    }

    /**
     * @return bool
     */
    public function isBuild() {
        $existsMainParams =  ($this->getEventType() && $this->getUser() && $this->getNotificationGenerator() && $this->getFcmMessage() && $this->getPushGenerator() && $this->getSessionGenerator());
        if ($existsMainParams) {
            switch ($this->getEventType()->getId()) {
                case 1:
                    return  (bool)$this->getUserStatus();
                case 2:
                    return  ($this->getAdvertisement() && $this->getAdvertisementStatus());
            }
        }
        return  false;
    }

    /**
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function manage() {
        if (!$this->isBuild()) {
            throw new \RuntimeException('Сборка обьекта NotificationPushDirector завершилсь неудачей!');
        }
        //генерация notification
        $this->notificationGenerator->setUser($this->getUser());
        $this->notificationGenerator->setEventType($this->getEventType());
        //$this->notificationGenerator->setNotificationMessage($notificationMessage);
        switch ($this->getEventType()->getId()) {
            case 1:
                $this->notificationGenerator->setUserStatus($this->getUserStatus());
                break;
            case 2:
                $this->notificationGenerator->setAdvertisement($this->getAdvertisement());
                $this->notificationGenerator->setAdvertisementStatus($this->getAdvertisementStatus());
                break;
        }
        $this->getNotificationGenerator()->generateNotification();
        if (!$notificationMessage = $this->notificationGenerator->getNotificationMessage()) {
            throw new \RuntimeException('Не получен обьект NotificationMessage');
        }

        //проверка NotificationSettings на отправку пуша
        //генерация push уведомления пока временно отключено /обьединить со следующим условием
        $token = $this->getUser()->getSession()? $this->getUser()->getSession()->getDeviceId() : '';
        if ($this->sessionGenerator->check($this->getUser()) && $token && false !== strpos($token, $_ENV['EXPO_PUSH_TOKEN_EXAMPLE'])) {
            $this->fcmMessage->build($notificationMessage);
            if (!$this->getFcmMessage()->isBuild()) {
                throw new \RuntimeException('Сборка обьекта FCMMessage завершилась неудачей!');
            }
            $this->pushGenerator->setFCMmessage($this->getFcmMessage());
            $this->pushGenerator->setTokens([$token]);
            $this->pushGenerator->generate();
        }
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
     * @return EventType
     */
    public function getEventType(): EventType
    {
        return $this->eventType;
    }

    /**
     * @param EventType $eventType
     */
    public function setEventType(EventType $eventType): void
    {
        $this->eventType = $eventType;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return NotificationGenerator
     */
    public function getNotificationGenerator(): NotificationGenerator
    {
        return $this->notificationGenerator;
    }

    /**
     * @param NotificationGenerator $notificationGenerator
     */
    public function setNotificationGenerator(NotificationGenerator $notificationGenerator): void
    {
        $this->notificationGenerator = $notificationGenerator;
    }

    /**
     * @return FCMMessage
     */
    public function getFcmMessage(): FCMMessage
    {
        return $this->fcmMessage;
    }

    /**
     * @param FCMMessage $fcmMessage
     */
    public function setFcmMessage(FCMMessage $fcmMessage): void
    {
        $this->fcmMessage = $fcmMessage;
    }

    /**
     * @return PushGenerator
     */
    public function getPushGenerator(): PushGenerator
    {
        return $this->pushGenerator;
    }

    /**
     * @param PushGenerator $pushGenerator
     */
    public function setPushGenerator(PushGenerator $pushGenerator): void
    {
        $this->pushGenerator = $pushGenerator;
    }

    /**
     * @return SessionGenerator
     */
    public function getSessionGenerator(): SessionGenerator
    {
        return $this->sessionGenerator;
    }

    /**
     * @param SessionGenerator $sessionGenerator
     */
    public function setSessionGenerator(SessionGenerator $sessionGenerator): void
    {
        $this->sessionGenerator = $sessionGenerator;
    }
}


