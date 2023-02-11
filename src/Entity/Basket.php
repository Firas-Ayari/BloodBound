<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\BasketRepository;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: BasketRepository::class)]
class Basket
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $totalNumber = null;

    #[ORM\Column]
    private ?float $totalPurchase = null;

    #[ManyToMany(targetEntity: Product::class, mappedBy: 'baskets')]
    private Collection $products;

    #[ORM\OneToOne(inversedBy: 'basket', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private Order|null $order = null;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTotalNumber(): ?int
    {
        return $this->totalNumber;
    }

    public function setTotalNumber(int $totalNumber): self
    {
        $this->totalNumber = $totalNumber;

        return $this;
    }

    public function getTotalPurchase(): ?float
    {
        return $this->totalPurchase;
    }

    public function setTotalPurchase(float $totalPurchase): self
    {
        $this->totalPurchase = $totalPurchase;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->addBasket($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            $product->removeBasket($this);
        }

        return $this;
    }

    public function getOrder(): ?Order
    {
        return $this->order;
    }

    public function setOrder(Order $order): self
    {
        $this->order = $order;

        return $this;
    }
}
