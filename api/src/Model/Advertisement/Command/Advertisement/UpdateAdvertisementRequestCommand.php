<?php
declare(strict_types=1);

namespace App\Model\Advertisement\Command\Advertisement;

use App\Model\Advertisement\Entity\Address;
use App\Model\Advertisement\Entity\BodyAdvertisement;
use App\Model\Advertisement\Entity\SubwayStation;
use App\Model\User\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateAdvertisementRequestCommand
{
    /**
     * @var int
     * @Assert\NotBlank()
     */
    public int $id = 1;

    /**
     * @var int
     * @Assert\Positive()
     */
    public ?int $categoryAdvertisementId = null;

    /**
     * @var UpdateBodyAdvertisementRequestCommand|null
     * @Assert\NotBlank()
     */
    public ?UpdateBodyAdvertisementRequestCommand $bodyAdvertisement = null;

    /**
     * @var UpdateAddressRequestCommand|null
     * @Assert\NotBlank()
     */
    public ?UpdateAddressRequestCommand $address = null;

    /**
     * @var SubwayStation|null
     */
    public ?SubwayStation $subwayStation = null;

    public ?array $photos = null;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return UpdateBodyAdvertisementRequestCommand|null
     */
    public function getBodyAdvertisement(): ?UpdateBodyAdvertisementRequestCommand
    {
        return $this->bodyAdvertisement;
    }

    /**
     * @param UpdateBodyAdvertisementRequestCommand|null $bodyAdvertisement
     */
    public function setBodyAdvertisement(?UpdateBodyAdvertisementRequestCommand $bodyAdvertisement): void
    {
        $this->bodyAdvertisement = $bodyAdvertisement;
    }

    /**
     * @return UpdateAddressRequestCommand|null
     */
    public function getAddress(): ?UpdateAddressRequestCommand
    {
        return $this->address;
    }

    /**
     * @param UpdateAddressRequestCommand|null $address
     */
    public function setAddress(?UpdateAddressRequestCommand $address): void
    {
        $this->address = $address;
    }

    /**
     * @return int|null
     */
    public function getCategoryAdvertisementId(): ?int
    {
        return $this->categoryAdvertisementId;
    }

    /**
     * @param int|null $categoryAdvertisementId
     */
    public function setCategoryAdvertisementId(?int $categoryAdvertisementId): void
    {
        $this->categoryAdvertisementId = $categoryAdvertisementId;
    }

    /**
     * @return SubwayStation|null
     */
    public function getSubwayStation():?SubwayStation
    {
        return $this->subwayStation;
    }

    /**
     * @param SubwayStation|null $subwayStation
     */
    public function setSubwayStation(?SubwayStation $subwayStation): void
    {
        $this->subwayStation = $subwayStation;
    }

    /**
     * @return array|null
     */
    public function getPhotos(): ?array
    {
        return $this->photos;
    }

    /**
     * @param array|null $photos
     */
    public function setPhotos(?array $photos): void
    {
        $this->photos = $photos;
    }

//    /**
//     * @return UpdatePhotoAdvertisementRequestCommand|null
//     */
//    public function getPhotos(): ?UpdatePhotoAdvertisementRequestCommand
//    {
//        return $this->photos;
//    }
//
//    /**
//     * @param UpdatePhotoAdvertisementRequestCommand|null $photos
//     */
//    public function setPhotos(?UpdatePhotoAdvertisementRequestCommand $photos): void
//    {
//        $this->photos = $photos;
//    }

//    public function __sleep()
//    {
//        return [];
//    }
}