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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
#[IsGranted('ROLE_ADMIN')]
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
    #[Route('/admin', name: 'app_user_list')]
    public function index(Request $request): Response
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        $numberOfUsersPerPage = 5;
        $totalUsers = count($users);
        $totalPages = ceil($totalUsers / $numberOfUsersPerPage);
        $pageNumber = $request->query->getInt('page', 1);
        $offset = ($pageNumber - 1) * $numberOfUsersPerPage;
        $limit = $numberOfUsersPerPage;
        $usersOnCurrentPage = array_slice($users, $offset, $limit);
    
        return $this->render('BackOffice/user/index.html.twig', [
            'users' => $usersOnCurrentPage,
            'totalPages' => $totalPages,
            'currentPage' => $pageNumber,
        ]);
    }


    #[Route('/create', name: 'app_user_create')]
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

        return $this->render('BackOffice/user/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_user_show')]
    public function show($id): Response
    {
        return $this->render('BackOffice/user/show.html.twig', [
            'user' => $this->userRepository->find($id),
        ]);
    }

    #[Route('/{id}/profile', name: 'app_user_show_na')]
    public function show_user($id): Response
    {
        return $this->render('FrontOffice/user/show.html.twig', [
            'user' => $this->userRepository->find($id),
        ]);
    }

    #[Route('/{id}/edit/admin', name: 'app_user_edit')]
    public function edit(Request $request, $id): Response
    {
        $user = $this->userRepository->find($id);
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            return $this->redirectToRoute('app_user_list');
        }

        return $this->renderForm('BackOffice/user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit_na')]
    public function edit_user(Request $request, $id): Response
    {
        $user = $this->userRepository->find($id);
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            return $this->redirectToRoute('app_user_list');
        }

        return $this->renderForm('FrontOffice/user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/archive', name:'app_user_archive')]
    public function archive($id): Response
    {
        $user = $this->userRepository->find($id);
        $user->setIsArchived(true);
        $this->em->flush();

        return $this->redirectToRoute('app_user_list');
    }

    #[Route('/{id}/ban', name:'app_user_ban')]
    public function ban($id): Response
    {
        $user = $this->userRepository->find($id);
        $user->setIsBanned(true);
        $this->em->flush();

        return $this->redirectToRoute('app_user_list');
    }

    #[Route('/{id}/unban', name:'app_user_unban')]
    public function unban($id): Response
    {
        $user = $this->userRepository->find($id);
        $user->setIsBanned(false);
        $this->em->flush();

        return $this->redirectToRoute('app_user_list');
    }
}
