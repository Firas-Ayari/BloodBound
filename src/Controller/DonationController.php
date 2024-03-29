<?php

namespace App\Controller;

use App\Entity\Donation;
use App\Form\DonationType;
use App\Repository\DonationRepository;
use App\Repository\EmergencyRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/donation')]
class DonationController extends AbstractController
{
    #[Route('/admin', name: 'app_donation_indexAdmin', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function indexAdmin(DonationRepository $donationRepository): Response
    {
        return $this->render('BackOffice/donation/index.html.twig', [
            'donations' => $donationRepository->findAll(),
        ]);
    }
    #[Route('/new/{id}', name: 'app_donation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, int $id, DonationRepository $donationRepository, EmergencyRepository $emergencyRepository): Response
    {
        $emergency = $emergencyRepository->find($id);
        $donation = new Donation();
        $donation->setEmergency($emergency);
        $form = $this->createForm(DonationType::class, $donation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$emergencyId = $donation->getEmergency()->getId();
            //$emergency = $emergencyRepository->find($emergencyId);
            $emergency->setStatus('completed');
            $donationRepository->save($donation, true);

            return $this->redirectToRoute('app_emergency_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('FrontOffice/donation/new.html.twig', [
            'donation' => $donation,
            'form' => $form,
        ]);
    }

    #[Route('/admin/{id}}', name: 'app_donation_show', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function show(Donation $donation): Response
    {
        return $this->render('BackOffice/donation/show.html.twig', [
            'donation' => $donation,
        ]);
    }

    #[Route('/admin/{id}/edit', name: 'app_donation_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, Donation $donation, DonationRepository $donationRepository): Response
    {
        $form = $this->createForm(DonationType::class, $donation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $donationRepository->save($donation, true);

            return $this->redirectToRoute('app_donation_indexAdmin', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('Backoffice/donation/edit.html.twig', [
            'donation' => $donation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_donation_delete', methods: ['POST'])]
    public function delete(Request $request, Donation $donation, DonationRepository $donationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$donation->getId(), $request->request->get('_token'))) {
            $donationRepository->remove($donation, true);
        }

        return $this->redirectToRoute('app_donation_indexAdmin', [], Response::HTTP_SEE_OTHER);
    }
}
