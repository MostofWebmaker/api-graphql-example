<?php
declare(strict_types=1);

namespace App\Model\Advertisement\Command\Advertisement;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateAddressRequestCommand
{
    /**
     * @var int
     * @Assert\NotBlank()
     */
    public int $id = 1;

    /**
     * @var string|null
     */
	public ?string $country = null;

    /**
     * @var string|null
     */
	public ?string $city = null;

    /**
     * @var string|null
     */
	public ?string $district = null;
    /**
     * @var string
     */
    public ?string $street = null;

    /**
     * @var string
     */
    public ?string $house = null;

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
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @param string|null $country
     */
    public function setCountry(?string $country): void
    {
        $this->country = $country;
    }

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string|null $city
     */
    public function setCity(?string $city): void
    {
        $this->city = $city;
    }

    /**
     * @return string|null
     */
    public function getDistrict(): ?string
    {
        return $this->district;
    }

    /**
     * @param string|null $district
     */
    public function setDistrict(?string $district): void
    {
        $this->district = $district;
    }

    /**
     * @return string|null
     */
    public function getStreet(): ?string
    {
        return $this->street;
    }

    /**
     * @param string|null $street
     */
    public function setStreet(?string $street): void
    {
        $this->street = $street;
    }

    /**
     * @return string|null
     */
    public function getHouse(): ?string
    {
        return $this->house;
    }

    /**
     * @param string|null $house
     */
    public function setHouse(?string $house): void
    {
        $this->house = $house;
    }
}