<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use App\Validator as AppAssert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
#[AppAssert\UniqueReservation()]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Assert\NotNull]
    #[Assert\GreaterThan('today', message: 'La date de début doit être supérieur à la date du jour.')]
    private $startAt;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Assert\NotNull]
    #[Assert\GreaterThan('today', message: 'La date de début doit être supérieur à la date du jour.')]
    #[Assert\Expression("this.getStartAt() < this.getEndAt()", message: "La date de début doit être avant la date de fin.")]
    private $endAt;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull]
    private $user;

    #[ORM\ManyToOne(targetEntity: Room::class, inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull]
    private $room;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartAt(): ?\DateTimeImmutable
    {
        return $this->startAt;
    }

    public function setStartAt(?\DateTimeImmutable $startAt): self
    {
        if ($startAt !== null) {
            $date = new \DateTimeImmutable($startAt->format('Y-m-d') . '12:00:01');
            $this->startAt = $date;
        } else {
            $this->startAt = $startAt;
        }

        return $this;
    }

    public function getEndAt(): ?\DateTimeImmutable
    {
        return $this->endAt;
    }

    public function setEndAt(?\DateTimeImmutable $endAt): self
    {
        if ($endAt !== null) {
            $date = new \DateTimeImmutable($endAt->format('Y-m-d') . '12:00:00');
            $this->endAt = $date;
        } else {
            $this->endAt = $endAt;
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getRoom(): ?Room
    {
        return $this->room;
    }

    public function setRoom(?Room $room): self
    {
        $this->room = $room;

        return $this;
    }

    public function getCanDelete(): Bool
    {
        $startAtNowDiff = $this->getStartAt()->diff(new \DateTime());
        return ($startAtNowDiff->invert > 0 && $startAtNowDiff->days > 2) ? true : false;
    }

    public function __toString(): string
    {
        return 'Réservation' . $this->id;
    }
}
