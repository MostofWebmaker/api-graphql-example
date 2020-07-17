<?php
declare(strict_types=1);

namespace App\Model\Advertisement\Command\Advertisement;

use Symfony\Component\Validator\Constraints as Assert;

class CreateAddressRequestCommand
{
    /**
     * @var string
     * @Assert\NotBlank()
     */
	public string $country = 'country';

    /**
     * @var string
     * @Assert\NotBlank()
     */
	public string $city = 'city';

    /**
     * @var string
     * @Assert\NotBlank()
     */
	public string $district = 'district';
    /**
     * @var string
     */
    public ?string $street = null;

    /**
     * @var string
     */
    public ?string $house = null;

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry(string $country): void
    {
        $this->country = $country;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getDistrict(): string
    {
        return $this->district;
    }

    /**
     * @param string $district
     */
    public function setDistrict(string $district): void
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