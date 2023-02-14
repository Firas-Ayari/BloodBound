<?php

namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Ticket;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\EventRepository;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
//controle saisie de titre
    #[ORM\Column(length: 30)]
    /**
     * @Assert\NotBlank(message = "Please enter a value.")
     * @Assert\Length(
     *     min=5,
     *     max=30,
     *     minMessage="Le titre doit comporter au moins {{ limit }} caractères",
     *     maxMessage="Le titre ne peut pas dépasser {{ limit }} caractères"
     * )
     */
    private ?string $title = null;
//controle saisie de description
    #[ORM\Column(length: 255)]
    /**
     * @Assert\NotBlank(message = "Please enter a value.")
     * @Assert\Length(
     *     min=20,
     *     max=255,
     *     minMessage="La description doit comporter au moins {{ limit }} caractères",
     *     maxMessage="Le description ne peut pas dépasser {{ limit }} caractères"
     * )
     */
    private ?string $description = null;
//controle saisie de statut
    #[ORM\Column(length: 255)]
    /**
     * @Assert\Choice(choices={"complet", "non complet"}, message="statut d'evenement invalide")
     */
    private ?string $status = null;
    
//contrôle saisie de Location
    #[ORM\Column(length: 40)]
    /**
     * @Assert\NotBlank(message = "Please enter a value.")
     * @Assert\Length(
     *     min=5,
     *     max=40,
     *     minMessage="La description d'emplacement doit comporter au moins {{ limit }} caractères",
     *     maxMessage="Le description d'emplacement ne peut pas dépasser {{ limit }} caractères"
     * )
     */
    private ?string $location = null;
//contrôle saisie de image
    #[ORM\Column(length: 60)]
    /**
     * @Assert\NotBlank(message = "Please enter an image.")
     * @Assert\Length(
     *     min=5,
     *     max=60,
     *     minMessage="image doit comporter au moins {{ limit }} caractères",
     *     maxMessage="image ne peut pas dépasser {{ limit }} caractères"
     * )
     */
    private ?string $image = null;
//contrôle saisie de date event 
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    /**
     * @Assert\NotBlank(message = "Please enter an event date.")
     */
    private ?\DateTimeInterface $eventDate = null;
//contrôle saisie de debut heure
    #[ORM\Column(type: Types::TIME_MUTABLE)]
    /**
     * @Assert\NotBlank(message = "Please enter start time.")
     */
    private ?\DateTimeInterface $startTime = null;
//contrôle saisie de fin heure
    #[ORM\Column(type: Types::TIME_MUTABLE)]
    /**
     * @Assert\NotBlank(message = "Please enter end time.")
     */
    private ?\DateTimeInterface $endTime = null;

    #[ManyToOne(targetEntity: User::class, inversedBy: 'events')]
    #[JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private User|null $user = null;

    #[ORM\OneToMany(mappedBy: 'event', targetEntity: Ticket::class)]
    private Collection $tickets;

    public function __construct()
    {
        $this->tickets = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getEventDate(): ?\DateTimeInterface
    {
        return $this->eventDate;
    }

    public function setEventDate(\DateTimeInterface $eventDate): self
    {
        $this->eventDate = $eventDate;

        return $this;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->startTime;
    }

    public function setStartTime(\DateTimeInterface $startTime): self
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getEndTime(): ?\DateTimeInterface
    {
        return $this->endTime;
    }

    public function setEndTime(\DateTimeInterface $endTime): self
    {
        $this->endTime = $endTime;

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

    /**
     * @return Collection<int, Ticket>
     */
    public function getTickets(): Collection
    {
        return $this->tickets;
    }

    public function addTicket(Ticket $ticket): self
    {
        if (!$this->tickets->contains($ticket)) {
            $this->tickets->add($ticket);
            $ticket->setEvent($this);
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): self
    {
        if ($this->tickets->removeElement($ticket)) {
            // set the owning side to null (unless already changed)
            if ($ticket->getEvent() === $this) {
                $ticket->setEvent(null);
            }
        }

        return $this;
    }
}
