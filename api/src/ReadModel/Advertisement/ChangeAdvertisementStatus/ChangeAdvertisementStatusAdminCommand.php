<?php

declare(strict_types=1);

namespace App\ReadModel\Advertisement\ChangeAdvertisementStatus;

class ChangeAdvertisementStatusAdminCommand
{
    /**
     * @var int|null
     */
    public ?int $statusId = null;
    /**
     * @var string|null
     */
    public ?string $message = null;

    /**
     * @return int|null
     */
    public function getStatusId(): ?int
    {
        return $this->statusId;
    }

    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }
}
