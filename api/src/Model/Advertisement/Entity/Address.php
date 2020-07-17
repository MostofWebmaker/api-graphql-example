<?php

namespace App\Model\Advertisement\Entity;

use App\Model\Advertisement\Entity\City;
use App\Model\Advertisement\Entity\Country;
use App\Model\Advertisement\Entity\District;
use App\Model\Advertisement\Entity\House;
use App\Model\Advertisement\Entity\Street;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="addresses", options={"collate"="utf8_general_ci"})
 * @ORM\Entity(repositoryClass="App\Model\Advertisement\Repository\AddressRepository")
 */
class Address
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Model\Advertisement\Entity\Country",cascade={"persist"})
     * @ORM\JoinColumn(name="country", nullable=false, unique=false)
	 */
    private Country $country;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Model\Advertisement\Entity\City",cascade={"persist"})
     * @ORM\JoinColumn(name="city", nullable=false, unique=false)
	 */
    private City $city;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Model\Advertisement\Entity\District",cascade={"persist"})
     * @ORM\JoinColumn(name="district", nullable=false, unique=false)
	 */
    private District $district;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Model\Advertisement\Entity\Street",cascade={"persist"})
     * @ORM\JoinColumn(name="street", nullable=true, unique=false)
	 */
	private ?Street $street = null;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Model\Advertisement\Entity\House",cascade={"persist"})
     * @ORM\JoinColumn(name="house", nullable=true, unique=false)
	 */
	private ?House $house = null;

    /**
     * Address constructor.
     * @param Country $country
     * @param City $city
     * @param District $district
     */
	public function __construct(Country $country, City $city, District $district)
	{
		$this->country = $country;
		$this->city = $city;
		$this->district = $district;
	}

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Country
     */
    public function getCountry(): Country
    {
        return $this->country;
    }

    /**
     * @param Country $country
     */
    public function setCountry(Country $country): void
    {
        $this->country = $country;
    }

    /**
     * @return City
     */
    public function getCity(): City
    {
        return $this->city;
    }

    /**
     * @param City $city
     */
    public function setCity(City $city): void
    {
        $this->city = $city;
    }

    /**
     * @return District
     */
    public function getDistrict(): District
    {
        return $this->district;
    }

    /**
     * @param District $district
     */
    public function setDistrict(District $district): void
    {
        $this->district = $district;
    }

    /**
     * @return Street|null
     */
    public function getStreet(): ?Street
    {
        return $this->street;
    }

    /**
     * @param mixed $street
     */
    public function setStreet($street): void
    {
        $this->street = $street;
    }

    /**
     * @return House|null
     */
    public function getHouse():?House
    {
        return $this->house;
    }

    /**
     * @param mixed $house
     */
    public function setHouse($house): void
    {
        $this->house = $house;
    }

    /**
     * @return string
     */
	public function __toString(): string
	{
	    $addressString = $this->getCountry()->getTitle().', г. '.$this->getCity()->getTitle();
	    $districtString = false !== stripos($this->getDistrict()->getTitle(), "район") ? $this->getDistrict()->getTitle() : $this->getDistrict()->getTitle(). ' район';
	    $street = ($this->getStreet() && $this->getStreet()->getTitle()) ? ', ул.'.$this->getStreet()->getTitle() : '';
	    $house = ($this->getHouse() && $this->getHouse()->getTitle()) ? ', д.'.$this->getHouse()->getTitle() : '';
	    return $addressString.', '.$districtString.$street.$house;
	}
}
