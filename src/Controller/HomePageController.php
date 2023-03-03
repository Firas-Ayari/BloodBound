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
        return $this->render('home_page/index.html.twig', [
            'controller_name' => 'HomePageController',
        ]);
    }
    #[Route('/admin', name: 'app_home_page')]
    public function indexAdmin(): Response
    {
        return $this->render('BackOffice/index.html.twig', [
            'controller_name' => 'HomePageController',
        ]);
    }
}
