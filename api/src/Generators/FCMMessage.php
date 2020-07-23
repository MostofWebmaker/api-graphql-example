<?php

namespace App\Generators;

/**
 * Class PushGenerator
 * @package App\Generators
 */
class FCMMessage
{
    /**
     * @var string
     */
    private string $title;

    /**
     * @var string
     */
    private string $message;

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
        return $this;
    }

    public function isBuild(): bool
    {
        return ($this->getTitle() && $this->getMessage());
    }

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
}
