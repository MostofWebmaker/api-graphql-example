<?php

namespace App\Model\Advertisement\Entity;

use Doctrine\ORM\Mapping as ORM;
use Monolog\DateTimeImmutable;

/**
 * @ORM\Table(name="advertisement_statuses", options={"collate"="utf8_general_ci"})
 * @ORM\Entity(repositoryClass="App\Model\Advertisement\Repository\AdvertisementStatusRepository")
 */
class AdvertisementStatus
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Model\Advertisement\Entity\AdvertisementStatusType", inversedBy="advertisementStatus")
     * @ORM\JoinColumn(nullable=false)
     */
    private AdvertisementStatusType $advertisementStatusType;

    /**
     * @var \DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     */
    private \DateTimeImmutable $dateCreated;

    /**
     * @var \DateTimeImmutable|null
     *  @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private ?\DateTimeImmutable $dateUpdated;
    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, unique=false, nullable=true)
     */
    private ?string $message = null;

    /**
     * AdvertisementStatus constructor.
     * @param AdvertisementStatusType $advertisementStatusType
     */
    public function __construct(AdvertisementStatusType $advertisementStatusType)
    {
    	$this->advertisementStatusType = $advertisementStatusType;
    	$this->dateCreated = new \DateTimeImmutable();
    }

	public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return AdvertisementStatusType
     */
    public function getAdvertisementStatusType(): AdvertisementStatusType
    {
        return $this->advertisementStatusType;
    }

    /**
     * @param AdvertisementStatusType $advertisementStatusType
     */
    public function setAdvertisementStatusType(AdvertisementStatusType $advertisementStatusType): void
    {
        $this->advertisementStatusType = $advertisementStatusType;
    }

	/**
	 * @return \DateTimeImmutable|null
	 */
	public function getDateUpdated(): ?\DateTimeImmutable
	{
		return $this->dateUpdated;
	}

	/**
	 * @param \DateTimeImmutable|null $dateUpdated
	 */
	public function setDateUpdated(?\DateTimeImmutable $dateUpdated): void
	{
		$this->dateUpdated = $dateUpdated;
	}

    /**
     * @return bool
     */
	public function isActive() {
       return ($this->getAdvertisementStatusType()->getId() === AdvertisementStatusType::STATUS_ACTIVE) || (($this->getAdvertisementStatusType()->getId() === AdvertisementStatusType::STATUS_PREMIUM_ACTIVE));
    }

    /**
     * @return bool
     */
    public function isBlocked() {
       return  ($this->getAdvertisementStatusType()->getId() === AdvertisementStatusType::STATUS_BANNED);
    }

    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @param string|null $message
     */
    public function setMessage(?string $message): void
    {
        $this->message = $message;
    }
}
