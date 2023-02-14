<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/products')]
class ProductController extends AbstractController
{
    private $em;
    private $productRepository;
    public function __construct(EntityManagerInterface $em, ProductRepository $productRepository) 
    {
        $this->em = $em;
        $this->productRepository = $productRepository;
    }
    #[Route('/', name: 'app_product_list')]
    public function index(): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $this->productRepository->findAll(),
        ]);
    }

    #[Route('/create', name: 'app_create_product')]
    public function new(Request $request): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($product);
            $this->em->flush();
            return $this->redirectToRoute('app_product_list');
        }

        return $this->render('product/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_show_product')]
    public function show($id): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $this->productRepository->find($id),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_product_edit')]
    public function edit(Request $request, $id): Response
    {
        $product = $this->productRepository->find($id);
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            return $this->redirectToRoute('app_product_list');
        }

        return $this->renderForm('product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name:'app_product_delete')]
    public function delete($id): Response
    {
        $product = $this->productRepository->find($id);
        $this->em->remove($product);
        $this->em->flush();

        return $this->redirectToRoute('app_product_list');
    }
}
