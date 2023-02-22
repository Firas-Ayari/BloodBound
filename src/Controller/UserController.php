<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\User1Type;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/users')]
class UserController extends AbstractController
{
    private $em;
    private $userRepository;
    public function __construct(EntityManagerInterface $em, UserRepository $userRepository) 
    {
        $this->em = $em;
        $this->userRepository = $userRepository;
    }
    #[Route('/', name: 'app_user_list')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $this->userRepository->findAll(),
        ]);
    }

    #[Route('/create', name: 'app_create_user')]
    public function new(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($user);
            $this->em->flush();
            return $this->redirectToRoute('app_user_list');
        }

        return $this->render('user/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_show_user')]
    public function show($id): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $this->userRepository->find($id),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit')]
    public function edit(Request $request, $id): Response
    {
        $user = $this->userRepository->find($id);
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            return $this->redirectToRoute('app_user_list');
        }

        return $this->renderForm('user/edit.html.twig', [
            'product' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name:'app_user_delete')]
    public function delete($id): Response
    {
        $user = $this->userRepository->find($id);
        $this->em->remove($user);
        $this->em->flush();

        return $this->redirectToRoute('app_user_list');
    }
}
