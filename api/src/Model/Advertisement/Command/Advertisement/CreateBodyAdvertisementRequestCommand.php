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

class CreateBodyAdvertisementRequestCommand
{
    /**
     * @var string
     * @Assert\NotBlank()
     */
    public string $title = 'title';

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public string $description = 'description';

    /**
     * @var float
     */
    public ?float $price = null;
    /**
     * @var string
     */
    public ?string $imageUrl = null;

    /**
     * @return string|null
     */
    public function getTitle(): ?string
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
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
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
     * @param float $price
     */
    public function setPrice(float $price): void
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
     * @param string $imageUrl
     */
    public function setImageUrl(string $imageUrl): void
    {
        $this->imageUrl = $imageUrl;
    }
}