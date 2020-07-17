<?php

namespace App\Model\Advertisement\Entity;

use App\Model\Advertisement\Entity\CategoryAdvertisement;
use App\Model\Advertisement\Entity\SubwayStation;
use App\Model\Advertisement\Entity\Address;
use App\Model\Message\Entity\Message;
use App\Model\User\Entity\User;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="advertisements", options={"collate"="utf8_general_ci"})
 * @ORM\Entity(repositoryClass="\App\Model\Advertisement\Repository\AdvertisementRepository")
 */
class Advertisement
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

	/**
	 * @Assert\NotBlank(message="Category advertisement cannot be blank.")
	 * @ORM\ManyToOne(targetEntity="App\Model\Advertisement\Entity\CategoryAdvertisement", inversedBy="advertisement")
     * @ORM\JoinColumn(name="category_advertisement", referencedColumnName="id", nullable=false)
	 */
    private CategoryAdvertisement $categoryAdvertisement;

    /**
	 * @Assert\NotBlank(message="Body advertisement cannot be blank.")
	 * @ORM\OneToOne(targetEntity="App\Model\Advertisement\Entity\BodyAdvertisement", cascade={"persist", "remove"})
	 */
    private BodyAdvertisement $bodyAdvertisement;

//	/**
//	 * @ORM\OneToMany(targetEntity="App\Model\Advertisement\Entity\PropertyGroup", cascade={"persist", "remove"}, mappedBy="advertisement")
//	 */
//    private ?Collection $propertyGroups = null;
//
//    /**
//	 * @ORM\OneToMany(targetEntity="App\Model\Advertisement\Entity\Property", cascade={"persist", "remove"}, mappedBy="advertisement")
//	 */
//    private ?Collection $properties = null;

	/**
	 * @Assert\NotBlank(message="Address cannot be blank.")
	 * @ORM\ManyToOne(targetEntity="App\Model\Advertisement\Entity\Address", cascade={"persist", "remove"})
	 */
    private Address $address;

    /**
	 * @ORM\ManyToOne(targetEntity="App\Model\Advertisement\Entity\SubwayStation", cascade={"persist"})
	 */
    private ?SubwayStation $subwayStation = null;

    //public

//    /**
//     * @ORM\ManyToMany(targetEntity="App\Model\User\Entity\User", mappedBy="User",  cascade={"persist", "remove"})
//     * @ORM\JoinColumn(name="interestedUser", referencedColumnName="id", nullable=true)
//     */
//    private ?Collection $interestedUsers = null;

    /**
     * @Assert\NotBlank(message="User cannot be blank.")
     * @ORM\ManyToOne(targetEntity="App\Model\User\Entity\User")
     */
    private User $user;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean", nullable=true)
	 */
    private bool $haveInViewed;

    /**
     * @var bool
     */
    private bool $isFavorite;

	/**
	 * @Assert\NotBlank(message="Advertisement status cannot be blank.")
	 * @ORM\OneToOne(targetEntity="App\Model\Advertisement\Entity\AdvertisementStatus", cascade={"persist", "remove"})
	 */
    private AdvertisementStatus $status;

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
     * @ORM\OneToMany(targetEntity="App\Model\Advertisement\Entity\Photo", mappedBy="advertisement", cascade={"persist"})
     * @ORM\JoinColumn(name="photo", referencedColumnName="id", nullable=true)
     */
    private Collection $photos;

    /**
     * @ORM\OneToMany(targetEntity="App\Model\Message\Entity\Message", mappedBy="advertisement")
     * @ORM\JoinColumn(name="message", referencedColumnName="id", nullable=true)
     */
    private Collection $messages;

    /**
     * Advertisement constructor.
     * @param CategoryAdvertisement $categoryAdvertisement
     * @param BodyAdvertisement $bodyAdvertisement
     * @param Address $address
     * @param User $user
     * @param AdvertisementStatus $advertisementStatus
     */
    public function __construct(
    	CategoryAdvertisement $categoryAdvertisement,
	    BodyAdvertisement $bodyAdvertisement,
	    Address $address,
	    User $user,
        AdvertisementStatus $advertisementStatus
    )
    {
    	$this->categoryAdvertisement = $categoryAdvertisement;
    	$this->bodyAdvertisement = $bodyAdvertisement;
    	$this->address = $address;
    	$this->user = $user;
    	$this->status = $advertisementStatus;
    	$this->dateCreated = new \DateTimeImmutable();
    }

	public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return CategoryAdvertisement
     */
    public function getCategoryAdvertisement(): CategoryAdvertisement
    {
        return $this->categoryAdvertisement;
    }

    /**
     * @param CategoryAdvertisement $categoryAdvertisement
     */
    public function setCategoryAdvertisement(CategoryAdvertisement $categoryAdvertisement): void
    {
        $this->categoryAdvertisement = $categoryAdvertisement;
    }

    /**
     * @return BodyAdvertisement
     */
    public function getBodyAdvertisement(): BodyAdvertisement
    {
        return $this->bodyAdvertisement;
    }

    /**
     * @param BodyAdvertisement $bodyAdvertisement
     */
    public function setBodyAdvertisement(BodyAdvertisement $bodyAdvertisement): void
    {
        $this->bodyAdvertisement = $bodyAdvertisement;
    }
//
//    /**
//     * @return PropertyGroup|null
//     */
//    public function getPropertyGroup(): ?PropertyGroup
//    {
//        return $this->propertyGroup;
//    }
//
//    /**
//     * @param PropertyGroup|null $propertyGroup
//     */
//    public function setPropertyGroup(?PropertyGroup $propertyGroup): void
//    {
//        $this->propertyGroup = $propertyGroup;
//    }
//
//    /**
//     * @return Property|null
//     */
//    public function getProperty(): ?Property
//    {
//        return $this->property;
//    }
//
//    /**
//     * @param Property|null $property
//     */
//    public function setProperty(?Property $property): void
//    {
//        $this->property = $property;
//    }

//    /**
//     * @return Collection|null
//     */
//    public function getProperties(): ?Collection
//    {
//        return $this->properties;
//    }
//
//    public function addProperty(Property $property): self
//    {
//        if (!$this->properties->contains($property)) {
//            $this->properties[] = $property;
//            $property->setAdvertisement($this);
//        }
//
//        return $this;
//    }
//
//    public function removeProperty(Property $property): self
//    {
//        if ($this->properties->contains($property)) {
//            $this->properties->removeElement($property);
//            // set the owning side to null (unless already changed)
//            if ($property->getAdvertisement() === $this) {
//                $property->setAdvertisement(null);
//            }
//        }
//
//        return $this;
//    }
//
//    /**
//     * @return Collection|null
//     */
//    public function getPropertyGroups(): ?Collection
//    {
//        return $this->propertyGroups;
//    }
//
//    public function addPropertyGroup(PropertyGroup $propertyGroup): self
//    {
//        if (!$this->properties->contains($propertyGroup)) {
//            $this->properties[] = $propertyGroup;
//            $propertyGroup->setAdvertisement($this);
//        }
//
//        return $this;
//    }
//
//    public function removePropertyGroup(PropertyGroup $propertyGroup): self
//    {
//        if ($this->properties->contains($propertyGroup)) {
//            $this->properties->removeElement($propertyGroup);
//            // set the owning side to null (unless already changed)
//            if ($propertyGroup->getAdvertisement() === $this) {
//                $propertyGroup->setAdvertisement(null);
//            }
//        }
//
//        return $this;
//    }

//    /**
//     * @return Collection|null
//     */
//    public function getPropertyGroup(): ?Collection
//    {
//        return $this->propertyGroup;
//    }
//
//    /**
//     * @param Collection|null $propertyGroup
//     */
//    public function setPropertyGroup(?Collection $propertyGroup): void
//    {
//        $this->propertyGroup = $propertyGroup;
//    }
//
//    /**
//     * @return Collection|null
//     */
//    public function getProperty(): ?Collection
//    {
//        return $this->property;
//    }
//
//    /**
//     * @param Collection|null $property
//     */
//    public function setProperty(?Collection $property): void
//    {
//        $this->property = $property;
//    }

//    /**
//     * @return mixed
//     */
//    public function getPropertyGroup()
//    {
//        return $this->propertyGroup;
//    }
//
//    /**
//     * @param mixed $propertyGroup
//     */
//    public function setPropertyGroup($propertyGroup): void
//    {
//        $this->propertyGroup = $propertyGroup;
//    }
//
//    /**
//     * @return mixed
//     */
//    public function getProperty()
//    {
//        return $this->property;
//    }
//
//    /**
//     * @param mixed $property
//     */
//    public function setProperty($property): void
//    {
//        $this->property = $property;
//    }

    /**
     * @return Address
     */
    public function getAddress(): Address
    {
        return $this->address;
    }

    /**
     * @param Address $address
     */
    public function setAddress(Address $address): void
    {
        $this->address = $address;
    }

    /**
     * @return \App\Model\Advertisement\Entity\SubwayStation|null
     */
    public function getSubwayStation(): ?SubwayStation
    {
        return $this->subwayStation;
    }

    /**
     * @param \App\Model\Advertisement\Entity\SubwayStation|null $subwayStation
     */
    public function setSubwayStation(?SubwayStation $subwayStation): void
    {
        $this->subwayStation = $subwayStation;
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
     * @return AdvertisementStatus
     */
    public function getStatus(): AdvertisementStatus
    {
        return $this->status;
    }

    /**
     * @param AdvertisementStatus $status
     */
    public function setStatus(AdvertisementStatus $status): void
    {
        $this->status = $status;
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
     * @return bool
     */
    public function isHaveInViewed(): bool
    {
        return $this->haveInViewed;
    }

    /**
     * @return bool
     */
    public function isFavorite(): bool
    {
        return $this->isFavorite ?? false;
    }

    /**
     * @param bool $isFavorite
     */
    public function setIsFavorite(bool $isFavorite)
    {
        $this->isFavorite = $isFavorite;
    }

    /**
     * @return Collection
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    /**
     * @param Photo $photo
     * @return $this
     */
    public function addPhoto(Photo $photo): self
    {
        if (!$this->photos->contains($photo)) {
            $this->photos[] = $photo;
            $photo->setAdvertisement($this);
        }

        return $this;
    }

    /**
     * @param Photo $photo
     * @return $this
     */
    public function removePhoto(Photo $photo): self
    {
        if ($this->photos->contains($photo)) {
            $this->photos->removeElement($photo);
            // set the owning side to null (unless already changed)
            if ($photo->getAdvertisement() === $this) {
                $photo->setAdvertisement(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    /**
     * @param Message $message
     * @return $this
     */
    public function addMessage(Message $message): self
    {
        if (!$this->photos->contains($message)) {
            $this->messages[] = $message;
            $message->setAdvertisement($this);
        }
        return $this;
    }

    /**
     * @param Message $message
     * @return $this
     */
    public function removeMessage(Message $message): self
    {
        if ($this->photos->contains($message)) {
            $this->photos->removeElement($message);
            // set the owning side to null (unless already changed)
            if ($message->getAdvertisement() === $this) {
                $message->setAdvertisement(null);
            }
        }
        return $this;
    }

    /**
     * @return array
     */
    public function __sleep()
    {
        return [];
    }

//    /**
//     * @return Collection|null
//     */
//    public function getInterestedUsers(): ?Collection
//    {
//        return $this->interestedUsers;
//    }
//
//    /**
//     * @param User $user
//     * @return $this
//     */
//    public function addInterestedUsers(User $user): self
//    {
//        if (!$this->interestedUsers->contains($user)) {
//            $this->interestedUsers[] = $user;
//            $user->addToFavoriteAdvertisements($this);
//        }
//
//        return $this;
//    }
//
//    /**
//     * @param User $user
//     * @return $this
//     */
//    public function removeFromInterestedUsers(User $user): self
//    {
//        if ($this->interestedUsers->contains($user)) {
//            $this->interestedUsers->removeElement($user);
//            // set the owning side to null (unless already changed)
//            if ($user->getFavoriteAdvertisements() === $this) {
//                $user->addToFavoriteAdvertisements(null);
//            }
//        }
//
//        return $this;
//    }
}
