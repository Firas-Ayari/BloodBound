<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/product')]
class ProductController extends AbstractController
{
    private $em;
    private $productRepository;
    public function __construct(EntityManagerInterface $em, ProductRepository $productRepository) 
    {
        $this->em = $em;
        $this->productRepository = $productRepository;
    }

    #[Route('/admin', name: 'app_product_index_admin', methods: ['GET'])]
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('BackOffice/product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);

        
    }

    #[Route('/', name: 'app_product_index', methods: ['GET'])]
    public function indexFront(ProductRepository $productRepository): Response
    {
        
        return $this->render('FrontOffice/product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);

        
    }

    #[Route('/new', name: 'app_product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProductRepository $productRepository): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($product);
            $this->em->flush();
            return $this->redirectToRoute('app_product_index_admin', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('BackOffice/product/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);

    }

    #[Route('/admin/{id}', name: 'app_product_show_admin', methods: ['GET'])]
    public function show(Product $product): Response
    {
        return $this->render('BackOffice/product/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/{id}', name: 'app_product_show', methods: ['GET'])]
    public function showFront(Product $product): Response
    {
        return $this->render('FrontOffice/product/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_product_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Product $product, ProductRepository $productRepository, $id): Response
    {
        $product = $this->productRepository->find($id);
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            return $this->redirectToRoute('app_product_index_admin', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('BackOffice/product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_product_delete', methods: ['POST'])]
    public function delete(Request $request, Product $product, ProductRepository $productRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $productRepository->remove($product, true);
        }

        return $this->redirectToRoute('app_product_index_admin', [], Response::HTTP_SEE_OTHER);
    }
}

