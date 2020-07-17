<?php

namespace App\Model\Notifications\Entity;

use App\Model\User\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Notifications
 * @package App\Model\Notifications\Entity
 * @ORM\Table(name="notifications", options={"collate"="utf8_general_ci"})
 * @ORM\Entity(repositoryClass="App\Model\Notifications\Repository\NotificationsRepository")
 */
class Notifications
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @Assert\NotBlank(message="User cannot be blank.")
     * @ORM\ManyToOne(targetEntity="App\Model\User\Entity\User")
     */
    private User $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Model\Notifications\Entity\EventType")
     * @ORM\JoinColumn(name="notifications", nullable=false, unique=false, onDelete="CASCADE")
     */
    private EventType $eventType;

    /**
     * @var string
     * @ORM\Column(type="string", name="title", length=255, nullable=false)
     */
    private string $sentTitle;

    /**
     * @var string
     * @ORM\Column(type="string", name="text", length=255, nullable=false)
     */
    private string $sentText;

    /**
     * @var \DateTimeImmutable
     * @ORM\Column(type="datetime_immutable", nullable=false)
     */
    private \DateTimeImmutable $dateCreated;

    /**
     * @var \DateTimeImmutable|null
     *  @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private ?\DateTimeImmutable $dateUpdated = null;

    /**
     * Notifications constructor.
     * @param User $user
     * @param EventType $eventType
     * @param string $sentTitle
     * @param string $sentText
     */
    public function __construct(User $user, EventType $eventType, string $sentTitle, string $sentText) {
        $this->user = $user;
        $this->eventType = $eventType;
        $this->sentTitle = $sentTitle;
        $this->sentText = $sentText;
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
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return EventType
     */
    public function getEventType(): EventType
    {
        return $this->eventType;
    }

    /**
     * @param EventType $eventType
     */
    public function setEventType(EventType $eventType): void
    {
        $this->eventType = $eventType;
    }

    /**
     * @return string
     */
    public function getSentTitle(): string
    {
        return $this->sentTitle;
    }

    /**
     * @param string $sentTitle
     */
    public function setSentTitle(string $sentTitle): void
    {
        $this->sentTitle = $sentTitle;
    }

    /**
     * @return string
     */
    public function getSentText(): string
    {
        return $this->sentText;
    }

    /**
     * @param string $sentText
     */
    public function setSentText(string $sentText): void
    {
        $this->sentText = $sentText;
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
}
