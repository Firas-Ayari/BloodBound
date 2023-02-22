<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToOne;
use App\Repository\OrderRepository;
use Doctrine\ORM\Mapping\JoinColumn;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $total = null;

    #[ORM\Column(length: 255)]
    private ?string $checkoutStatus = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[OneToOne(targetEntity: Basket::class, inversedBy: 'order')]
    #[JoinColumn(name: 'basket_id', referencedColumnName: 'id')]
    private Basket|null $basket = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getCheckoutStatus(): ?string
    {
        return $this->checkoutStatus;
    }

    public function setCheckoutStatus(string $checkoutStatus): self
    {
        $this->checkoutStatus = $checkoutStatus;

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

    public function getBasket(): ?Basket
    {
        return $this->basket;
    }

    public function setBasket(Basket $basket): self
    {
        // set the owning side of the relation if necessary
        if ($basket->getOrder() !== $this) {
            $basket->setOrder($this);
        }

        $this->basket = $basket;

        return $this;
    }
}
