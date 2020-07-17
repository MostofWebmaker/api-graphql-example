<?php

namespace App\Model\Advertisement\Entity;

use Doctrine\ORM\Mapping as ORM;
use DomainException;

/**
 * @ORM\Table(name="body_advertisements", options={"collate"="utf8_general_ci"})
 * @ORM\Entity(repositoryClass="App\Model\Advertisement\Repository\BodyAdvertisementRepository")
 */
class BodyAdvertisement
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=255)
	 */
    private string $title;

	/**
	 * @var float|null
	 * @ORM\Column(type="float", nullable=true)
	 */
    private ?float $price;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=255)
	 */
    private string $description;

	/**
	 * @var array
	 * @ORM\Column(type="array")
	 */
    private ?array $arrImageUrl = null;

    public function __construct(string $title,  string $description)
    {
    	if (empty($title) || empty($description)) {
    		throw new DomainException('Не заполнены обязательные поля заголовка или описания обьявления!');
	    }
	    $this->title = $title;
        $this->description =  $description;
    }

	/**
	 * @return mixed
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param mixed $id
	 */
	public function setId($id): void
	{
		$this->id = $id;
	}

	/**
	 * @return string
	 */
	public function getTitle(): string
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
	 * @return string
	 */
	public function getDescription(): string
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
     * @return array|null
     */
    public function getArrImageUrl(): ?array
    {
        return $this->arrImageUrl;
    }

    /**
     * @param array|null $arrImageUrl
     */
    public function setArrImageUrl(?array $arrImageUrl): void
    {
        $this->arrImageUrl = $arrImageUrl;
    }
}
