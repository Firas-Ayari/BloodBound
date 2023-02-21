<?php

namespace App\Entity;

use App\Repository\DonationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

#[ORM\Entity(repositoryClass: DonationRepository::class)]
class Donation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Please enter a value.')]
    #[Assert\Length(
        min: 15,
        max: 255,
        minMessage: 'The description must be at least {{ limit }} characters',
        maxMessage: 'The description cannot exceed {{ limit }} characters'
    )]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Please enter a value.')]
    #[Assert\Length(
        min: 5,
        max: 40,
        minMessage: 'Location description must be at least {{ limit }} characters',
        maxMessage: 'Location description cannot exceed {{ limit }} characters'
    )]
    private ?string $donLocation = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Please enter your email')]
    #[Assert\Email(
        message: 'The email {{ value }} is not a valid email.',
    )]
    private ?string $email = null;

    #[ORM\Column(type: Types::BIGINT)]
    #[Assert\NotBlank(message: 'Please enter your number')]
    #[Assert\Regex(
        pattern:"/^\d{8}$/",
        message:"The phone number '{{ value }}' is not a valid phone number."
    )]
    private ?string $phoneNumber = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $donationDate = null;

    #[ManyToOne(targetEntity: Emergency::class, inversedBy: 'donations')]
    #[JoinColumn(name: 'emergency_id', referencedColumnName: 'id', onDelete:"CASCADE")]
    private Emergency|null $emergency = null;

    public function __construct()
    {
        $this->donationDate = new \DateTimeImmutable();
    }

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