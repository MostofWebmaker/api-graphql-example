<?php
declare(strict_types=1);

namespace App\Model\Advertisement\Command\Advertisement;

use Symfony\Component\Validator\Constraints as Assert;

class AdvertisementStatusChangeRequestCommand
{
    /**
     * @var int
     * @Assert\NotBlank()
     */
    public int $advertisementId = 1;

    /**
     * @var int
     * @Assert\NotBlank()
     */
    public int $statusId = 1;

    /**
     * @var int
     * @Assert\NotBlank()
     */
    public int $userId = 1;
    /**
     * @var string|null
     */
    public ?string $message = null;

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @return int
     */
    public function getAdvertisementId(): int
    {
        return $this->advertisementId;
    }

    /**
     * @param int $advertisementId
     */
    public function setAdvertisementId(int $advertisementId): void
    {
        $this->advertisementId = $advertisementId;
    }

    /**
     * @return int
     */
    public function getStatusId(): int
    {
        return $this->statusId;
    }

    /**
     * @param int $statusId
     */
    public function setStatusId(int $statusId): void
    {
        $this->statusId = $statusId;
    }


    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @param string|null $message
     */
    public function setMessage(?string $message): void
    {
        $this->message = htmlspecialchars($message, ENT_NOQUOTES);
    }
}