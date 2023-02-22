<?php

namespace App\Entity;
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

    #[ManyToOne(targetEntity: Event::class, inversedBy: 'tickets')]
    #[JoinColumn(name: 'event_id', referencedColumnName: 'id', onDelete:"CASCADE")]
    private Event|null $event = null;

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

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;

        return $this;
    }
}