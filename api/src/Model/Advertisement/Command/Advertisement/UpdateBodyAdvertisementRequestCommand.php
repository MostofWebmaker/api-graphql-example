<?php
declare(strict_types=1);

namespace App\Model\Advertisement\Command\Advertisement;

use App\Model\Advertisement\Entity\Address;
use App\Model\Advertisement\Entity\AdvertisementStatus;
use App\Model\Advertisement\Entity\BodyAdvertisement;
use App\Model\Advertisement\Entity\CategoryAdvertisement;
use App\Model\Advertisement\Entity\SubwayStation;
use App\Model\User\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateBodyAdvertisementRequestCommand
{
    /**
     * @var int
     * @Assert\NotBlank()
     */
    public int $id = 1;

    /**
     * @var string|null
     */
    public ?string $title = null;

    /**
     * @var string|null
     */
    public ?string $description = null;

    /**
     * @var float
     */
    public ?float $price = null;
    /**
     * @var string
     */
    public ?string $imageUrl = null;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return float|null
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /**
     * @param float|null $price
     */
    public function setPrice(?float $price): void
    {
        $this->price = $price;
    }

    /**
     * @return string|null
     */
    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    /**
     * @param string|null $imageUrl
     */
    public function setImageUrl(?string $imageUrl): void
    {
        $this->imageUrl = $imageUrl;
    }
}