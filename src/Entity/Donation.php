<?php

namespace App\Entity;

use App\Repository\DonationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DonationRepository::class)]
class Donation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $donLocation = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $phoneNumber = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $donationDate = null;

    #[ManyToOne(targetEntity: Emergency::class, inversedBy: 'donations')]
    #[JoinColumn(name: 'emergency_id', referencedColumnName: 'id')]
    private Emergency|null $emergency = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDonLocation(): ?string
    {
        return $this->donLocation;
    }

    public function setDonLocation(string $donLocation): self
    {
        $this->donLocation = $donLocation;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getDonationDate(): ?\DateTimeImmutable
    {
        return $this->donationDate;
    }

    public function setDonationDate(\DateTimeImmutable $donationDate): self
    {
        $this->donationDate = $donationDate;

        return $this;
    }

    public function getEmergency(): ?Emergency
    {
        return $this->emergency;
    }

    public function setEmergency(?Emergency $emergency): self
    {
        $this->emergency = $emergency;

        return $this;
    }
}
