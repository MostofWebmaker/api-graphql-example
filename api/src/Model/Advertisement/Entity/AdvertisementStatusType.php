<?php

namespace App\Model\Advertisement\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Table(name="advertisement_status_types", options={"collate"="utf8_general_ci"})
 * @ORM\Entity(repositoryClass="App\Model\Advertisement\Repository\AdvertisementStatusTypeRepository")
 */
class AdvertisementStatusType
{
	public const STATUS_BANNED = 1;
	public const STATUS_DRAFT = 2;
	public const STATUS_ON_MODERATION = 3;
	public const STATUS_ACTIVE = 4;
	public const STATUS_IN_ARCHIVE = 5;
	public const STATUS_PREMIUM_ACTIVE = 6;

	// List of all statuses of a advertisement with name in Russian
	public const ADVERTISEMENT_ALL_STATUS_LIST = [
		self::STATUS_BANNED => 'Заблокировано',
		self::STATUS_DRAFT => 'Черновик',
		self::STATUS_ON_MODERATION => 'На модерации',
		self::STATUS_ACTIVE => 'Активировано',
		self::STATUS_IN_ARCHIVE => 'Перенесено в архив',
		self::STATUS_PREMIUM_ACTIVE => 'Премиум активация'
	];
	public const ADVERTISEMENT_USER_STATUS_LIST = [
		self::STATUS_DRAFT => 'Черновик',
		self::STATUS_ON_MODERATION => 'На модерации',
		self::STATUS_IN_ARCHIVE => 'Перенесено в архив'
	];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @var string
     * @ORM\Column(name="description", type="string")
     */
    private string $description;

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
     * @ORM\OneToMany(targetEntity="App\Model\Advertisement\Entity\AdvertisementStatus", mappedBy="advertisementStatusType")
     */
    private ?Collection $advertisementStatuses = null;

    /**
     * AdvertisementStatusType constructor.
     * @param string $description
     */
    public function __construct(string $description)
    {
        $this->description = $description;
        $this->dateCreated = new \DateTimeImmutable();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getDateCreated(): \DateTimeImmutable
    {
        return $this->dateCreated;
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
     * @return Collection|null
     */
    public function getAdvertisementStatuses(): ?Collection
    {
        return $this->advertisementStatuses;
    }

    /**
     * @param AdvertisementStatus $advertisementStatus
     * @return $this
     */
    public function addAdvertisementStatus(AdvertisementStatus $advertisementStatus): self
    {
        if (!$this->advertisementStatuses->contains($advertisementStatus)) {
            $this->advertisementStatuses[] = $advertisementStatus;
            $advertisementStatus->setAdvertisementStatusType($this);
        }
        return $this;
    }

    /**
     * @param AdvertisementStatus $advertisementStatus
     * @return $this
     */
    public function removeAdvertisementStatus(AdvertisementStatus $advertisementStatus): self
    {
        if ($this->advertisementStatuses->contains($advertisementStatus)) {
            $this->advertisementStatuses->removeElement($advertisementStatus);
            // set the owning side to null (unless already changed)
            if ($advertisementStatus->getAdvertisementStatusType() === $this) {
                $advertisementStatus->setAdvertisementStatusType(null);
            }
        }
        return $this;
    }
}
