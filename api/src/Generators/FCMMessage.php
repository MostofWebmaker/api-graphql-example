<?php

namespace App\Generators;

/**
 * Class PushGenerator
 * @package App\Generators
 */
class FCMMessage
{
//    /**
//     * @var NotificationMessage
//     */
//    private NotificationMessage $notificationMessage;

    /**
     * @var string
     */
    private string $title;

    /**
     * @var string
     */
    private string $message;
//    /**
//     * @var string
//     */
//    private string $subTitle;
//    /**
//     * @var int
//     */
//    private int $vibrate;
//    /**
//     * @var int
//     */
//    private int $sound;
//    /**
//     * @var string
//     */
//    private string $largeIcon;
//    /**
//     * @var string
//     */
//    private string $smallIcon;

    public function __construct()
    {
    }

    /**
     * @param NotificationMessage $notificationMessage
     * @return $this
     */
    public function build(NotificationMessage $notificationMessage) {
        $this->title = $notificationMessage->getTitle();
        $this->message = $notificationMessage->getBody();
//        $this->subTitle = $subTitle;
//        $this->vibrate = $vibrate;
//        $this->sound = $sound;
//        $this->largeIcon = $largeIcon;
//        $this->smallIcon = $smallIcon;
        return $this;
    }

    public function isBuild(): bool
    {
        return ($this->getTitle() && $this->getMessage());
    }


//    /**
//     * @return NotificationMessage
//     */
//    public function getNotificationMessage(): NotificationMessage
//    {
//        return $this->notificationMessage;
//    }
//
//    /**
//     * @param NotificationMessage $notificationMessage
//     */
//    public function setNotificationMessage(NotificationMessage $notificationMessage): void
//    {
//        $this->notificationMessage = $notificationMessage;
//    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

//    /**
//     * @return string
//     */
//    public function getSubTitle(): string
//    {
//        return $this->subTitle;
//    }
//
//    /**
//     * @param string $subTitle
//     */
//    public function setSubTitle(string $subTitle): void
//    {
//        $this->subTitle = $subTitle;
//    }
//
//    /**
//     * @return int
//     */
//    public function getVibrate(): int
//    {
//        return $this->vibrate;
//    }
//
//    /**
//     * @param int $vibrate
//     */
//    public function setVibrate(int $vibrate): void
//    {
//        $this->vibrate = $vibrate;
//    }
//
//    /**
//     * @return int
//     */
//    public function getSound(): int
//    {
//        return $this->sound;
//    }
//
//    /**
//     * @param int $sound
//     */
//    public function setSound(int $sound): void
//    {
//        $this->sound = $sound;
//    }
//
//    /**
//     * @return string
//     */
//    public function getLargeIcon(): string
//    {
//        return $this->largeIcon;
//    }
//
//    /**
//     * @param string $largeIcon
//     */
//    public function setLargeIcon(string $largeIcon): void
//    {
//        $this->largeIcon = $largeIcon;
//    }
//
//    /**
//     * @return string
//     */
//    public function getSmallIcon(): string
//    {
//        return $this->smallIcon;
//    }
//
//    /**
//     * @param string $smallIcon
//     */
//    public function setSmallIcon(string $smallIcon): void
//    {
//        $this->smallIcon = $smallIcon;
//    }
}
