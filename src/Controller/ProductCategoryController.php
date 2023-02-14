<?php

namespace App\Controller;

use App\Entity\ProductCategory;
use App\Form\ProductCategoryType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ProductCategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/product-category')]
class ProductController extends AbstractController
{
    private $em;
    private $productCategoryRepository;
    public function __construct(EntityManagerInterface $em, ProductCategoryRepository $productCategoryRepository) 
    {
        $this->em = $em;
        $this->productCategoryRepository = $productCategoryRepository;
    }
    #[Route('/', name: 'app_product_category_list')]
    public function index(): Response
    {
        return $this->render('product_category/index.html.twig', [
            'product_categories' => $this->productCategoryRepository->findAll(),
        ]);
    }

    #[Route('/create', name: 'app_create_product_category')]
    public function new(Request $request): Response
    {
        $productCategory = new ProductCategory();
        $form = $this->createForm(ProductCategoryType::class, $productCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($productCategory);
            $this->em->flush();
            return $this->redirectToRoute('app_product_category_list');
        }

        return $this->render('product_category/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_show_product_category')]
    public function show($id): Response
    {
        return $this->render('product_category/show.html.twig', [
            'product_category' => $this->productCategoryRepository->find($id),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_product_category_edit')]
    public function edit(Request $request, $id): Response
    {
        $productCategory = $this->productCategoryRepository->find($id);
        $form = $this->createForm(ProductCategoryType::class, $productCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            return $this->redirectToRoute('app_product_category_list');
        }

        return $this->renderForm('product_category/edit.html.twig', [
            'product_category' => $productCategory,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name:'app_product_category_delete')]
    public function delete($id): Response
    {
        $productCategory = $this->productCategoryRepository->find($id);
        $this->em->remove($productCategory);
        $this->em->flush();

        return $this->redirectToRoute('app_product_category_list');
    }
}
