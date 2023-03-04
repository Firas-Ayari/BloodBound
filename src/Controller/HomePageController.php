<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomePageController extends AbstractController
{
    #[Route('/', name: 'app_home_page')]
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

    #[Route('/ticket', name: 'app_ticket')]
    public function indextickey(): Response
    {
        return $this->render('ticket.html.twig', [
            'home_page' => 'HomePageController',
        ]);
    }
}
