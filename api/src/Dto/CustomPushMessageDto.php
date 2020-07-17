<?php

namespace App\Dto;

/**
 * Class CustomPushMessageDto
 * @package App\Dto
 */
class CustomPushMessageDto
{
    /**
     * @var string
     */
    private string $title;
    /**
     * @var string
     */
    private string $body;

    /**
     * CustomPushMessageDto constructor.
     * @param int $title
     * @param string $body
     */
    public function __construct(int $title, string $body)
    {
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
}