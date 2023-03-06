<?php

namespace App\Services;

use App\Entity\Basket;
use App\Entity\CartProduct;
use App\Entity\Product;
use App\Repository\BasketRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CartProductRepository;
use Symfony\Component\HttpFoundation\Request;

class CartCalculator
{
    public function __construct(
        private ProductRepository $productRepository,
        private CartProductRepository $cartProductRepository,
        private BasketRepository $cartRepository,
        private EntityManagerInterface $entityManager
    )
    {}

    public function TotalPriceCalcul(Basket $cart)
    {
        $total = 0;
        $cartproducts = $cart->getCartProducts();
        foreach($cartproducts as $cartproduct)
        {
            $product = $cartproduct->getProduct();
            $total = $total + ($product->getPrice()*$cartproduct->getQuantity());
        }
        return $total;
    }
}