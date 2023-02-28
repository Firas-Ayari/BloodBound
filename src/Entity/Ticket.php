<?php

namespace App\Entity;
//use App\Entity\Achat;
use App\Entity\Event;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use App\Repository\TicketRepository;
use Doctrine\ORM\Mapping\JoinColumn;

#[ORM\Entity(repositoryClass: TicketRepository::class)]
class Ticket
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
//controle saisie de price
    #[ORM\Column]
    #[Assert\Type(
        type: 'float',
        message: 'The value {{ value }} is not a valid {{ type }}.',
    )]
    private ?float $price = null;
//controle saisie de stock
    #[ORM\Column]
    #[Assert\Type(
        type: 'int',
        message: 'The value {{ value }} is not a valid {{ type }}.',
    )]
    private ?int $stock = null;
//controle saisie de status
    #[ORM\Column(length: 255)]
    #[Assert\Choice(choices: ['available', 'sold out'], message: 'Invalid ticket status.')]
    private ?string $status = null;

    #[OneToOne(targetEntity: Event::class, inversedBy: 'ticket')]
    #[JoinColumn(name: 'event_id', referencedColumnName: 'id')]
    private Event|null $event = null;
    
    #[ORM\OneToMany(mappedBy: 'ticket', targetEntity: Achat::class)]
    private Collection $achats;

    public function __construct()
    {
        $this->achats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

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

    /**
     * @return Collection<int, Achat>
     */
    public function getAchats(): Collection
    {
        return $this->achats;
    }

    public function addAchat(Achat $achat): self
    {
        if (!$this->achats->contains($achat)) {
            $this->achats->add($achat);
            $achat->setTicket($this);
        }

        return $this;
    }

    public function removeAchat(Achat $achat): self
    {
        if ($this->achats->removeElement($achat)) {
            // set the owning side to null (unless already changed)
            if ($achat->getTicket() === $this) {
                $achat->setTicket(null);
            }
        }

        return $this;
    }

}