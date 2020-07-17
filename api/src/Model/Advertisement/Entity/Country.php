<?php

namespace App\Model\Advertisement\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="countries", options={"collate"="utf8_general_ci"})
 * @ORM\Entity(repositoryClass="\App\Model\Advertisement\Repository\CountryRepository")
 */
class Country
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=255, unique=false)
	 */
	private string $title;

	/**
	 * City constructor.
	 * @param string $title
	 */
	public function __construct(string $title)
	{
		$this->title = $title;
	}

	/**
	 * @return mixed
	 */
	public function getId()
	{
		return $this->id;
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

}
