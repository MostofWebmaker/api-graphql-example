<?php

namespace App\Model\Advertisement\ValueObject;

use App\Model\Advertisement\Entity\Address;
use App\Model\Advertisement\Entity\SubwayStation;
use Doctrine\ORM\Mapping as ORM;

class Location
{
    /**
	 * @ORM\OneToOne(targetEntity="App\Model\Advertisement\Entity\SubwayStation", cascade={"persist", "remove"})
	 */
    private SubwayStation $subwayStation;

    /**
	 * @ORM\OneToOne(targetEntity="App\Model\Advertisement\Entity\Address", cascade={"persist", "remove"})
	 */
    private Address $address;

    /**
     * Location constructor.
     * @param SubwayStation $subwayStation
     * @param Address $address
     */
    public function __construct(SubwayStation $subwayStation, Address $address)
    {
    	$this->subwayStation = $subwayStation;
    	$this->address = $address;
    }

    /**
     * @return SubwayStation
     */
    public function getSubwayStation(): SubwayStation
    {
        return $this->subwayStation;
    }

    /**
     * @return Address
     */
    public function getAddress(): Address
    {
        return $this->address;
    }
}
