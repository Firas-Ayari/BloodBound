<?php

namespace App\Controller;

use COM;
use App\Entity\Basket;
use App\Entity\CartProduct;
use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Services\CartCalculator;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;



#[Route('/product')]
class ProductController extends AbstractController
{
    private $em;
    private $productRepository;
    private $priceCalculator;
    public function __construct(EntityManagerInterface $em, ProductRepository $productRepository, CartCalculator $cartCalculator) 
    {
        $this->em = $em;
        $this->productRepository = $productRepository;
        $this->priceCalculator = $cartCalculator;
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
    public function new(Request $request, ProductRepository $productRepository, SluggerInterface $slugger): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            

            //upload img
            $image = $form->get('image')->getData();

            // this condition is needed because the 'image' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();

                // Move the file to the directory where images are stored
                try {
                    $image->move(
                        $this->getParameter('img_directory'),
                        $newFilename
                    );

                    
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'imageename' property to store img name
                // instead of its contents
                $product->setImage($newFilename);
                
            }

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

    #[Route('/{id}/addtocart', name: 'app_product_cart')]
    public function addToCart($id): Response
    {
        $cart = new Basket();
        $cart->setCreatedAt(new DateTimeImmutable());
        $cart->setCheckout("onHold");
        $product = $this->productRepository->find($id);
        if($product->getStock() > 0)
        {
            $cartProduct = new CartProduct();
            $cartProduct->setProduct($product);
            $cartProduct->setCart($cart);
            $cartProduct->setQuantity(1);
            $cart->setTotal($cart->getTotal()+$product->getPrice());
            //$cart->setTotal($this->priceCalculator->TotalPriceCalcul($cart));
            $product->setStock($product->getStock()-1);
            $this->em->persist($cart);
            $this->em->persist($cartProduct);
            $this->em->flush();
        }
        return $this->redirectToRoute('app_product_index');
    }

    #[Route('/{id}/addtocart/{quantity}', name: 'app_product_cart_quantity')]
    public function addToCartByQuantity($id,$quantity): Response
    {
        $cart = new Basket();
        $cart->setCreatedAt(new DateTimeImmutable());
        $cart->setCheckout("onHold");
        $product = $this->productRepository->find($id);
        if($product->getStock() >= $quantity)
        {
            $cartProduct = new CartProduct();
            $cartProduct->setProduct($product);
            $cartProduct->setCart($cart);
            $cartProduct->setQuantity($quantity);
            $product->setStock($product->getStock()-$quantity);
            $cart->setTotal($cart->getTotal()+($product->getPrice()*$quantity));
            //$cart->setTotal($this->priceCalculator->TotalPriceCalcul($cart));
            $this->em->persist($cart);
            $this->em->persist($cartProduct);
            $this->em->flush();
        }
        return $this->redirectToRoute('app_product_index');
    }

    #[Route('/{id}/edit', name: 'app_product_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Product $product, ProductRepository $productRepository, $id , SluggerInterface $slugger): Response
    {
        $product = $this->productRepository->find($id);
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
                        //upload img
                        $image = $form->get('image')->getData();

                        // this condition is needed because the 'image' field is not required
                        // so the PDF file must be processed only when a file is uploaded
                        if ($image) {
                            $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                            // this is needed to safely include the file name as part of the URL
                            $safeFilename = $slugger->slug($originalFilename);
                            $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();
            
                            // Move the file to the directory where images are stored
                            try {
                                $image->move(
                                    $this->getParameter('img_directory'),
                                    $newFilename
                                );
            
                                
                            } catch (FileException $e) {
                                // ... handle exception if something happens during file upload
                            }
            
                            // updates the 'imageename' property to store img name
                            // instead of its contents
                            $product->setImage($newFilename);
                            
                        }
            
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

