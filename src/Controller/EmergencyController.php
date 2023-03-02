<?php

namespace App\Controller;

use App\Entity\Emergency;
use App\Form\EmergencyType;
use App\Repository\EmergencyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/emergency')]
class EmergencyController extends AbstractController
{
    #[Route('/admin', name: 'app_emergency_indexAdmin', methods: ['GET'])]
    public function indexAdmin(EmergencyRepository $emergencyRepository): Response
    {
        return $this->render('BackOffice/emergency/index.html.twig', [
            'emergencies' => $emergencyRepository->findAll(),
        ]);
    }
    #[Route('/', name: 'app_emergency_index', methods: ['GET'])]
    public function index(EmergencyRepository $emergencyRepository): Response
    {
        return $this->render('FrontOffice/emergency/index.html.twig', [
            'emergencies' => $emergencyRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_emergency_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EmergencyRepository $emergencyRepository): Response
    {
        $emergency = new Emergency();
        $form = $this->createForm(EmergencyType::class, $emergency);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $emergencyRepository->save($emergency, true);

            return $this->redirectToRoute('app_emergency_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('FrontOffice/emergency/new.html.twig', [
            'emergency' => $emergency,
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'app_emergency_show', methods: ['GET'])]
    public function show(Emergency $emergency): Response
    {
        $emergency->incrementViewCount();
        $this->getDoctrine()->getManager()->flush();

        return $this->render('FrontOffice/emergency/show.html.twig', [
            'emergency' => $emergency,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_emergency_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Emergency $emergency, EmergencyRepository $emergencyRepository): Response
    {
        $form = $this->createForm(EmergencyType::class, $emergency);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $emergencyRepository->save($emergency, true);

            return $this->redirectToRoute('app_emergency_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('FrontOffice/emergency/edit.html.twig', [
            'emergency' => $emergency,
            'form' => $form,
        ]);
    }
    #[Route('/admin/{id}/edit', name: 'app_emergency_editAdmin', methods: ['GET', 'POST'])]
    public function editAdmin(Request $request, Emergency $emergency, EmergencyRepository $emergencyRepository): Response
    {
        $form = $this->createForm(EmergencyType::class, $emergency);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $emergencyRepository->save($emergency, true);

            return $this->redirectToRoute('app_emergency_indexAdmin', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('BackOffice/emergency/edit.html.twig', [
            'emergency' => $emergency,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_emergency_delete', methods: ['POST'])]
    public function delete(Request $request, Emergency $emergency, EmergencyRepository $emergencyRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$emergency->getId(), $request->request->get('_token'))) {
            $emergencyRepository->remove($emergency, true);
        }

        return $this->redirectToRoute('app_emergency_indexAdmin', [], Response::HTTP_SEE_OTHER);
    }
}
