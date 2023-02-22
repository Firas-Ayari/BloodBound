<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/event')]
class EventController extends AbstractController
{
    #[Route('/admin', name: 'app_event_index_admin', methods: ['GET'])]
    public function indexAdmin(EventRepository $eventRepository): Response
    {
        return $this->render('BackOffice/event/index.html.twig', [
            'events' => $eventRepository->findAll(),
        ]);
    }

    #[Route('/', name: 'app_event_index', methods: ['GET'])]
    public function index(EventRepository $eventRepository): Response
    {
        return $this->render('FrontOffice/event/index.html.twig', [
            'events' => $eventRepository->findAll(),
        ]);
    }


    #[Route('/admin/new', name: 'app_event_new_admin', methods: ['GET', 'POST'])]
    public function newAdmin(Request $request, EventRepository $eventRepository): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $eventRepository->save($event, true);

            return $this->redirectToRoute('app_event_index_admin', [], Response::HTTP_SEE_OTHER);
        }
        

        return $this->renderForm('BackOffice/event/new.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/admin/{id}', name: 'app_event_show_admin', methods: ['GET'])]
    public function showAdmin(Event $event): Response
    {
        $tickets = $event->getTickets();
        return $this->render('BackOffice/event/show.html.twig', [
            'event' => $event,
            'tickets' => $tickets
        ]);
    }

    #[Route('/{id}', name: 'app_event_show', methods: ['GET'])]
    public function show(Event $event): Response
    {
        $tickets = $event->getTickets();
        return $this->render('FrontOffice/event/show.html.twig', [
            'event' => $event,
            'tickets' => $tickets
        ]);
    }

    #[Route('/admin/{id}/edit', name: 'app_event_editAdmin', methods: ['GET', 'POST'])]
    public function editAdmin(Request $request, Event $event, EventRepository $eventRepository): Response
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $eventRepository->save($event, true);

            return $this->redirectToRoute('app_event_index_admin', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('BackOffice/event/edit.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_event_delete', methods: ['POST'])]
    public function delete(Request $request, Event $event, EventRepository $eventRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->request->get('_token'))) {
            $eventRepository->remove($event, true);
        }

        return $this->redirectToRoute('app_event_index_admin', [], Response::HTTP_SEE_OTHER);
    }
}