<?php

namespace App\Model\Notifications\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class EventType
 * @package App\Model\Notifications\Entity
 * @ORM\Table(name="event_types", options={"collate"="utf8_general_ci"})
 * @ORM\Entity(repositoryClass="App\Model\Notifications\Repository\EventTypeRepository")
 */
class EventType
{
    public const USER_STATUS_CHANGED = 1;
    public const ADVERTISEMENT_STATUS_CHANGED = 2;
    public const YOU_HAVE_NEW_MESSAGE = 3;

    public const EVENT_TYPE_LIST = [
        self::USER_STATUS_CHANGED => 'Изменился Ваш статус пользователя',
        self::ADVERTISEMENT_STATUS_CHANGED => 'Изменился статус Вашего объявления',
        self::YOU_HAVE_NEW_MESSAGE => 'По вашему объявлению поступило сообщение(я) от нового пользователя'
    ];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $description = null;

    /**
     * @var \DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     */
    private \DateTimeImmutable $dateCreated;

    /**
     * @var \DateTimeImmutable|null
     *  @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private ?\DateTimeImmutable $dateUpdated = null;

    /**
     * EventType constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
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
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getDateCreated(): \DateTimeImmutable
    {
        return $this->dateCreated;
    }

    /**
     * @param \DateTimeImmutable $dateCreated
     */
    public function setDateCreated(\DateTimeImmutable $dateCreated): void
    {
        $this->dateCreated = $dateCreated;
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
     * @ORM\OneToMany(targetEntity="App\Model\Notifications\Entity\Notifications", mappedBy="eventType")
     */
    private ?Collection $notifications = null;

    /**
     * @return Collection|Notifications[]
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    /**
     * @param Notifications $notification
     * @return $this
     */
    public function addNotification(Notifications $notification): self
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications[] = $notification;
            $notification->setEventType($this);
        }
        return $this;
    }

    /**
     * @param Notifications $notification
     * @return $this
     */
    public function removeNotification(Notifications $notification): self
    {
        if ($this->notifications->contains($notification)) {
            $this->notifications->removeElement($notification);
            // set the owning side to null (unless already changed)
            if ($notification->getEventType() === $this) {
                $notification->setEventType(null);
            }
        }
        return $this;
    }
}
