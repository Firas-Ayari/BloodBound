<?php

namespace App\Controller;

use App\Entity\Basket;
use App\Form\BasketType;
use App\Entity\CartProduct;
use App\Services\CartService;
use App\Repository\BasketRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/basket')]
class BasketController extends AbstractController
{
    private $em;
    private $productRepository;
    private $cartService;
    public function __construct(EntityManagerInterface $em, ProductRepository $productRepository, CartService $cartService) 
    {
        $this->em = $em;
        $this->productRepository = $productRepository;
        $this->cartService = $cartService;
    }
    /*#[Route('/', name: 'app_basket_index', methods: ['GET'])]
    public function index(BasketRepository $basketRepository): Response
    {
        return $this->render('FrontOffice/basket/index.html.twig', [
            'baskets' => $basketRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_basket_new', methods: ['GET', 'POST'])]
    public function new(Request $request, BasketRepository $basketRepository): Response
    {
        $basket = new Basket();
        $form = $this->createForm(BasketType::class, $basket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $basketRepository->save($basket, true);

            return $this->redirectToRoute('app_basket_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('basket/new.html.twig', [
            'basket' => $basket,
            'form' => $form,
        ]);
    }*/

    #[Route('/{id}', name: 'app_basket_show', methods: ['GET'])]
    public function show(Basket $basket): Response
    {
        $cartproducts = $basket->getCartProducts();
        return $this->render('/FrontOffice/basket/show.html.twig', [
            'basket' => $basket,
            'cartproducts' => $cartproducts
        ]);
    }

    #[Route('/{id}/editquantity', name: 'app_quantity_edit', methods: ['GET'])]
    public function editQuantity(Request $request,$id): Response
    {

        $cartproduct = $this->em->getRepository(CartProduct::class)->find($id);
        if ($request->isMethod("post")) 
        {
            $quantity=$request->get('quantity');
        }
        $cartproduct->setQuantity($quantity);
        $cartproduct->setTotal($cartproduct->getProduct()->getPrice()*$quantity);
        $this->em->flush();

        return $this->redirectToRoute('app_basket_show');
        
    }
    /*#[Route('/{id}/edit', name: 'app_basket_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Basket $basket, BasketRepository $basketRepository): Response
    {
        $form = $this->createForm(BasketType::class, $basket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $basketRepository->save($basket, true);

            return $this->redirectToRoute('app_basket_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('basket/edit.html.twig', [
            'basket' => $basket,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_basket_delete', methods: ['POST'])]
    public function delete(Request $request, Basket $basket, BasketRepository $basketRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$basket->getId(), $request->request->get('_token'))) {
            $basketRepository->remove($basket, true);
        }

        return $this->redirectToRoute('app_basket_index', [], Response::HTTP_SEE_OTHER);
    }*/
}
