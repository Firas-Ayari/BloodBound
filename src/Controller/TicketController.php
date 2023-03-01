<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Achat;
use App\Entity\Ticket;
use App\Form\TicketType;
use App\Repository\TicketRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/ticket')]
class TicketController extends AbstractController
{
    #[Route('/admin', name: 'app_ticket_indexAdmin', methods: ['GET'])]
    public function indexAdmin(TicketRepository $ticketRepository): Response
    {
        return $this->render('BackOffice/ticket/index.html.twig', [
            'tickets' => $ticketRepository->findAll(),
        ]);
    }
    
    #[Route('/', name: 'app_ticket_index', methods: ['GET'])]
    public function index(TicketRepository $ticketRepository): Response
    {
        return $this->render('FrontOffice/ticket/index.html.twig', [
            'tickets' => $ticketRepository->findAll(),
        ]);
    }

    #[Route('/admin/new', name: 'app_ticket_newAdmin', methods: ['GET', 'POST'])]
    public function new(Request $request, TicketRepository $ticketRepository): Response
    {
        $ticket = new Ticket();
        $form = $this->createForm(TicketType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ticketRepository->save($ticket, true);

            return $this->redirectToRoute('app_ticket_indexAdmin', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('BackOffice/ticket/new.html.twig', [
            'ticket' => $ticket,
            'form' => $form,
        ]);
    }

    #[Route('/admin/{id}', name: 'app_ticket_show_admin', methods: ['GET'])]
    public function showAdmin(Ticket $ticket): Response
    {
      return $this->render('BackOffice/ticket/show.html.twig', [
            'ticket' => $ticket,
        ]);
   }
    
    #[Route('/{id}', name: 'app_ticket_show', methods: ['GET'])]
    public function show(Ticket $ticket): Response
    {
        return $this->render('FrontOffice/ticket/show.html.twig', [
            'ticket' => $ticket,
        ]);
    }

    #[Route('/admin/{id}/edit', name: 'app_ticket_editAdmin', methods: ['GET', 'POST'])]
    public function editAdmin(Request $request, Ticket $ticket, TicketRepository $ticketRepository): Response
    {
        $form = $this->createForm(TicketType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ticketRepository->save($ticket, true);

            return $this->redirectToRoute('app_ticket_indexAdmin', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('BackOffice/ticket/edit.html.twig', [
            'ticket' => $ticket,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ticket_delete', methods: ['POST'])]
    public function delete(Request $request, Ticket $ticket, TicketRepository $ticketRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ticket->getId(), $request->request->get('_token'))) {
            $ticketRepository->remove($ticket, true);
        }

        return $this->redirectToRoute('app_ticket_indexAdmin', [], Response::HTTP_SEE_OTHER);
    }

    /**
 * @Route("/buyticket/{id}", name="app_buyticket", methods={"GET", "POST"})
 */
public function buyticket(Request $request, Ticket $ticket): Response
{
    $stock = $ticket->getStock();
    
    if ($stock > 0) {
        $ticket->setStock($stock - 1);
        
        if ($stock - 1 === 0) {
            $ticket->setStatus('sold out');
            $event = $ticket->getEvent();
        if ($event) {
            $event->setStatus('complet');
        }
        }
        
        $achat = new Achat();
        $achat->setTicket($ticket);
        $achat->setUser($this->getUser());
        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($achat);
        $entityManager->flush();
        
        $this->addFlash('success', 'You have successfully purchased a ticket.');
    } else {
        $this->addFlash('error', 'The tickets are sold out.');
    }
    
    return $this->redirectToRoute('app_ticket_show', ['id' => $ticket->getId()], Response::HTTP_SEE_OTHER);
}
 }
