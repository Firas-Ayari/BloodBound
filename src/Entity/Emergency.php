<?php

namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use App\Repository\EmergencyRepository;

#[ORM\Entity(repositoryClass: EmergencyRepository::class)]
class Emergency
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
//controle saisie de titre
    #[ORM\Column(length: 20)]
    /**
     * @Assert\NotBlank(message="Le Titre est obligatoire")
     * @Assert\Length(max=20, maxMessage="Le Titre ne peut pas dépasser 20 caractères")
     */
    private ?string $title = null;

//contrôle saisie de description
    #[ORM\Column(length: 255)]
    /**
     * @Assert\NotBlank(message="La description est obligatoire")
     *  min = 20,
     *     max = 255,
     *     minMessage = "La description minimum est 20 caractères",
     *     maxMessage = "La description ne peut pas dépasser 255 caractères"
     */
    private ?string $description = null;

    //contrôle saisie de type de sang
    #[ORM\Column(length: 5)]
    /**
     * @Assert\Choice(choices={"A+", "A-", "B+", "B-", "AB+", "AB-", "O+", "O-"}, message="Type de sang invalide")
     */
    private ?string $bloodType = null;

    //contrôle saisie de Location
    #[ORM\Column(length: 40)]
    /**
     * @Assert\NotBlank(message="La description est obligatoire")
     *  min = 5,
     *     max = 40,
     *     minMessage = "Emplacement description minimum est 5 caractères",
     *     maxMessage = "Emplacement description ne peut pas dépasser 40 caractères"
     */
    private ?string $location = null;
//contrôle saisie de date
    #[ORM\Column(type: Types::DATE_MUTABLE)]

    private ?\DateTimeInterface $deadline = null;

    //contrôle saisie de status
    #[ORM\Column(length: 255)]
    /**
     * @Assert\Choice(choices={"pas commencé", "en cours", "complété"}, message="Statut de traitement invalide")
     */
    private ?string $status = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ManyToOne(targetEntity: User::class, inversedBy: 'emergencies')]
    #[JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private User|null $user = null;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getBloodType(): ?string
    {
        return $this->bloodType;
    }

    public function setBloodType(string $bloodType): self
    {
        $this->bloodType = $bloodType;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getDeadline(): ?\DateTimeInterface
    {
        return $this->deadline;
    }

    public function setDeadline(\DateTimeInterface $deadline): self
    {
        $this->deadline = $deadline;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

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
}
