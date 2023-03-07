<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomePageController extends AbstractController
{
    #[Route('/', name: 'app_home_page')]
    //#[IsGranted('ROLE_ADMIN')]
    public function index(): Response
    {
        return $this->render('/FrontOffice/homePage.html.twig', [
            'home_page' => 'HomePageController',
        ]);
    }

    #[Route('/invoice', name: 'app_invoice')]
    public function indexinvoice(): Response
    {
        return $this->render('invoice.html.twig', [
            'home_page' => 'HomePageController',
        ]);
    }
}
