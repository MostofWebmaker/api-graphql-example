<?php

namespace App\Generators;

/**
 * Class NotificationMessage
 * @package App\Generators
 */
class NotificationMessage
{
    /**
     * @var string
     */
    private ?string $title = null;
    /**
     * @var string
     */
    private ?string $body = null;

//    /**
//     * NotificationMessage constructor.
//     * @param int $title
//     * @param string $body
//     */
//    public function __construct(int $title, string $body)
//    {
//        $this->title = $title;
//        $this->body = $body;
//    }

    /**
     * NotificationMessage constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param string $title
     * @param string $body
     */
    public function build (string $title, string $body)
    {
        if (empty($title) || empty($body)) {
            throw new \RuntimeException('Аргументы функции не должны быть пустыми!');
        }
        $this->title = $title;
        $this->body = $body;
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
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $body
     */
    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    /**
     * @return bool
     */
    public function isBuild(): bool
    {
        return ($this->getTitle() && $this->getBody());
    }
}