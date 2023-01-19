<?php

namespace App\Entity;

use App\Repository\BookingRepository;
use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;

#[ORM\Entity(repositoryClass: BookingRepository::class)]
class Booking
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?DateTimeImmutable $beginAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $endAt = null;

    #[ORM\Column(length: 255, nullable:true)]
    private ?string $title = null;

    #[ORM\ManyToOne(inversedBy: 'bookings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $formateur = null;

    #[ORM\ManyToOne(inversedBy: 'bookings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Centre $centre = null;

    #[ORM\ManyToOne(inversedBy: 'bookings')]
    private ?Promo $promo = null;

    #[ORM\ManyToOne(inversedBy: 'bookings')]
    private ?Cours $cours = null;

    #[ORM\Column]
    private ?bool $matin = null;

    #[ORM\Column]
    private ?bool $aprem = null;

    #[ORM\Column]
    private ?bool $journee = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBeginAt(): ?\DateTimeImmutable
    {
        return $this->beginAt;
    }

    public function setBeginAt(\DateTimeImmutable $beginAt): self
    {
        $this->beginAt = $beginAt;

        return $this;
    }

    public function getEndAt(): ?\DateTimeImmutable
    {
        return $this->endAt;
    }

    public function setEndAt(?\DateTimeImmutable $endAt): self
    {
        $this->endAt = $endAt;

        return $this;
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

    public function getFormateur(): ?User
    {
        return $this->formateur;
    }

    public function setFormateur(?User $formateur): self
    {
        $this->formateur = $formateur;

        return $this;
    }

    public function getCentre(): ?Centre
    {
        return $this->centre;
    }

    public function setCentre(?Centre $centre): self
    {
        $this->centre = $centre;

        return $this;
    }

    public function getPromo(): ?Promo
    {
        return $this->promo;
    }

    public function setPromo(?Promo $promo): self
    {
        $this->promo = $promo;

        return $this;
    }

    public function getCours(): ?Cours
    {
        return $this->cours;
    }

    public function setCours(?Cours $cours): self
    {
        $this->cours = $cours;

        return $this;
    }

    public function isMatin(): ?bool
    {
        return $this->matin;
    }

    public function setMatin(bool $matin): self
    {
        $this->matin = $matin;

        return $this;
    }

    public function isAprem(): ?bool
    {
        return $this->aprem;
    }

    public function setAprem(bool $aprem): self
    {
        $this->aprem = $aprem;

        return $this;
    }

    public function isJournee(): ?bool
    {
        return $this->journee;
    }

    public function setJournee(bool $journee): self
    {
        $this->journee = $journee;

        return $this;
    }

}
