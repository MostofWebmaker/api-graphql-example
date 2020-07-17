<?php
declare(strict_types=1);

namespace App\Model\Advertisement\Command\Advertisement;

use Symfony\Component\Validator\Constraints as Assert;

class UpdatePhotoAdvertisementRequestCommand
{
    /**
     * @var int
     * @Assert\Positive()
     * @Assert\NotBlank()
     */
    public int $id = 1;

    /**
     * @var string
     */
    public ?string $photo = null;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string|null
     */
    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    /**
     * @param string|null $photo
     */
    public function setPhoto(?string $photo): void
    {
        $this->photo = $photo;
    }
}