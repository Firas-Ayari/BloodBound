<?php

namespace App\Controller;

use App\Entity\Facility;
use App\Form\FacilityType;
use App\Repository\FacilityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/facility')]
class FacilityController extends AbstractController
{
    #[Route('/admin', name: 'app_facility_indexAdmin', methods: ['GET'])]
    public function index(FacilityRepository $facilityRepository): Response
    {
        return $this->render('BackOffice/facility/index.html.twig', [
            'facilities' => $facilityRepository->findAll(),
        ]);
    }

    #[Route('/', name: 'app_emergency_index', methods: ['GET'])]
    public function indexAdmin(FacilityRepository $facilityRepository): Response
    {
        return $this->render('FrontOffice/facility/index.html.twig', [
            'emergencies' => $facilityRepository->findAll(),
        ]);
    }

    #[Route('/admin/new', name: 'app_facility_new', methods: ['GET', 'POST'])]
    public function new(Request $request, FacilityRepository $facilityRepository): Response
    {
        $facility = new Facility();
        $form = $this->createForm(FacilityType::class, $facility);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $facilityRepository->save($facility, true);

            return $this->redirectToRoute('app_facility_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('BackOffice/facility/new.html.twig', [
            'facility' => $facility,
            'form' => $form,
        ]);
    }



    #[Route('/{id}', name: 'app_facility_show', methods: ['GET'])]
    public function show(Facility $facility): Response
    {
        return $this->render('BackOffice/facility/show.html.twig', [
            'facility' => $facility,
        ]);
    }



    #[Route('/admin/{id}/edit', name: 'app_facility_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Facility $facility, FacilityRepository $facilityRepository): Response
    {
        $form = $this->createForm(FacilityType::class, $facility);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $facilityRepository->save($facility, true);

            return $this->redirectToRoute('app_facility_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('BackOffice/facility/edit.html.twig', [
            'facility' => $facility,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_facility_delete', methods: ['POST'])]
    public function delete(Request $request, Facility $facility, FacilityRepository $facilityRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$facility->getId(), $request->request->get('_token'))) {
            $facilityRepository->remove($facility, true);
        }

        return $this->redirectToRoute('app_facility_index', [], Response::HTTP_SEE_OTHER);
    }
}