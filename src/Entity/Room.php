<?php

namespace App\Entity;

use App\Repository\RoomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: RoomRepository::class)]
#[ORM\HasLifecycleCallbacks()]
#[Vich\Uploadable]
#[UniqueEntity(
    fields: ['establishment', 'title'],
    errorPath: 'title',
    message: 'Cet établissement a déjà une suite avec ce nom',
)]
class Room
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 50)]
    #[Assert\Length(min: 5, max: 50)]
    private $title;

    #[Vich\UploadableField(mapping: 'picture', fileNameProperty: 'featuredImageName')]
    private ?File $featuredImageFile = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $featuredImageName;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    #[Assert\NotBlank(message: "Veuillez renseigner le prix")]
    private $price;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Length(min: 5, max: 255)]
    #[Assert\NotBlank(message: "Veuillez renseigner le lien Booking")]
    private $bookingLink;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank(message: "Veuillez renseigner la description")]
    private $description;

    #[ORM\Column(type: 'datetime_immutable')]
    private $updatedAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private $createdAt;

    #[ORM\ManyToOne(targetEntity: Establishment::class, inversedBy: 'rooms')]
    #[ORM\JoinColumn(nullable: false)]
    private $establishment;

    #[ORM\OneToMany(mappedBy: 'room', targetEntity: Picture::class, cascade: ["persist"], orphanRemoval: true)]
    #[Assert\Valid()]
    private $pictures;

    public function __construct()
    {
        $this->pictures = new ArrayCollection();
    }

    #[ORM\PreUpdate]
    #[ORM\PrePersist]
    public function updatedTimestamps(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt(new \DateTimeImmutable('now'));
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getFeaturedImageFile(): ?File
    {
        return $this->featuredImageFile;
    }

    public function setFeaturedImageFile(?File $featuredImageFile = null): void
    {
        $this->featuredImageFile = $featuredImageFile;

        if (null !== $featuredImageFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getFeaturedImageName(): ?string
    {
        return $this->featuredImageName;
    }

    public function setFeaturedImageName(?string $featuredImageName): self
    {
        $this->featuredImageName = $featuredImageName;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getBookingLink(): ?string
    {
        return $this->bookingLink;
    }

    public function setBookingLink(string $bookingLink): self
    {
        $this->bookingLink = $bookingLink;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getEstablishment(): ?Establishment
    {
        return $this->establishment;
    }

    public function setEstablishment(?Establishment $establishment): self
    {
        $this->establishment = $establishment;

        return $this;
    }

    /**
     * @return Collection<int, Picture>
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    public function addPicture(Picture $picture): self
    {
        if (!$this->pictures->contains($picture)) {
            $this->pictures[] = $picture;
            $picture->setRoom($this);
        }

        return $this;
    }

    public function removePicture(Picture $picture): self
    {
        if ($this->pictures->removeElement($picture)) {
            // set the owning side to null (unless already changed)
            if ($picture->getRoom() === $this) {
                $picture->setRoom(null);
            }
        }

        return $this;
    }
}
