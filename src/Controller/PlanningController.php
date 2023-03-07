<?php

namespace App\Controller;

use App\Entity\Planning;
use App\Form\PlanningType;
use App\Repository\PlanningRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Facility;

#[Route('/planning')]
class PlanningController extends AbstractController
{
    #[Route('/admin', name: 'app_planning_index', methods: ['GET'])]
    public function indexadmin(PlanningRepository $planningRepository): Response
    {
        return $this->render('BackOffice/planning/index.html.twig', [
            'plannings' => $planningRepository->findAll(),
        ]);
    }

    #[Route('/', name: 'app_planning_index', methods: ['GET'])]
    public function index(PlanningRepository $planningRepository): Response
    {
        return $this->render('FrontOffice/planning/index.html.twig', [
            'plannings' => $planningRepository->findAll(),
        ]);
    }

    #[Route('/admin/new', name: 'app_planning_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PlanningRepository $planningRepository): Response
    {
        $planning = new Planning();
        $form = $this->createForm(PlanningType::class, $planning);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $planningRepository->save($planning, true);

            return $this->redirectToRoute('app_planning_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('BackOffice/planning/new.html.twig', [
            'planning' => $planning,
            'form' => $form,
        ]);
    }

    #[Route('admin/{id}', name: 'app_planning_show', methods: ['GET'])]
    public function showAdmin(Planning $planning): Response
    {
        return $this->render('BackOffice/planning/show.html.twig', [
            'planning' => $planning,
        ]);
    }

    #[Route('/{id}', name: 'app_planning_show', methods: ['GET'])]
    public function show(Planning $planning): Response
    {
        return $this->render('FrontOffice/planning/show.html.twig', [
            'planning' => $planning,
        ]);
    }

    

    #[Route('/{id}/edit', name: 'app_planning_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Planning $planning, PlanningRepository $planningRepository): Response
    {
        $form = $this->createForm(PlanningType::class, $planning);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $planningRepository->save($planning, true);

            return $this->redirectToRoute('app_planning_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('planning/edit.html.twig', [
            'planning' => $planning,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_planning_delete', methods: ['POST'])]
    public function delete(Request $request, Planning $planning, PlanningRepository $planningRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$planning->getId(), $request->request->get('_token'))) {
            $planningRepository->remove($planning, true);
        }

        return $this->redirectToRoute('app_planning_index', [], Response::HTTP_SEE_OTHER);
    }
}
