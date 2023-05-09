<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Achat;
use App\Entity\Ticket;
use App\Entity\User;
use App\Form\TicketType;
use App\Repository\TicketRepository;
use App\Repository\AchatRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
//use App\Service\TwilioService;
use Twilio\Rest\Client;
use libphonenumber\PhoneNumberUtil;
use Dompdf\Dompdf;
use Dompdf\Options;


#[Route('/ticket')]
class TicketController extends AbstractController
{
    #[Route('/admin', name: 'app_ticket_indexAdmin', methods: ['GET'])]
    public function indexAdmin(TicketRepository $ticketRepository, EntityManagerInterface $entityManager): Response
    {   
        // Ticket Status Staistics
        $availableTickets = $this->getDoctrine()
            ->getRepository(Ticket::class)
            ->count(['status' => 'available']);
        $soldOutTickets = $this->getDoctrine()
        ->getRepository(Ticket::class)
        ->count(['status' => 'sold out']);

        //Ticket Status 2
        $query = $entityManager->createQuery(
            'SELECT t.id, t.stock, COUNT(p.id) as achats
            FROM App\Entity\Ticket t
            JOIN App\Entity\Achat p WITH p.ticket = t.id
            GROUP BY t.id'
        );

        $ticketsData = $query->getResult();

        $data = [
            'labels' => array_map(function($ticketData) { return $ticketData['id']; }, $ticketsData),
            'datasets' => [
                [
                    'label' => 'Number of Purchases',
                    'data' => array_map(function($ticketData) { return $ticketData['achats']; }, $ticketsData),
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'borderWidth' => 1,
                ]
            ]
        ];



        return $this->render('BackOffice/ticket/index.html.twig', [
            'tickets' => $ticketRepository->findAll(),
            'availableTickets' => $availableTickets,
            'soldOutTickets' => $soldOutTickets,
            'data' => json_encode($data),
            
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
    public function show(Ticket $ticket, AchatRepository $achatRepository): Response
    {
        //$sumOfTickets = $achatRepository->getSumOfTicketsPurchasedByUser($ticket);
        $count = $achatRepository->countTicketsPurchasedForTicketId($ticket);
        return $this->render('FrontOffice/ticket/show.html.twig', [
            'ticket' => $ticket,
            'count' => $count,
            //'status' => $ticket->getStatus()
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
public function buyticketDT(Request $request, Ticket $ticket): Response
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
        
        // Send Twilio SMS notification
        $sid = 'AC3fcc859c3c9d7ca0f904d5969b8f077b';
        $token = '4408d07f6b27129cf416cb48fcca0d07';
        $from = '+15673714926';
        $to = '+21650205982';
        
        $client = new Client($sid, $token);
        $event = $ticket->getEvent();
        $message = $client->messages->create(
            $to,
            array(
                'from' => $from,
                'body' => 'You have successfully purchased a ticket for '.$event->getTitle().' at '.$ticket->getPrice().' dinars.',
            )
        );

        // Pdf Download
        $ticket = $this->getDoctrine()->getRepository(Ticket::class)->find($ticket);

        if (!$ticket) {
            throw $this->createNotFoundException('Ticket not found');
        }

        $event = $ticket->getEvent();

        $pdfOptions = new Dompdf();
        $html = $this->renderView('FrontOffice/ticket/ticketpdf.html.twig', [
            'event' => $event,
            'ticket' => $ticket,
            'achat' => $achat,
        ]);
        $pdfOptions->loadHtml($html);
        $pdfOptions->setPaper('A4', 'portrait');




        $pdfOptions->render();

        $pdfContent = $pdfOptions->output();
        $response = new Response($pdfContent);

        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment;filename=ticket.pdf');

        return $response; 

        $this->addFlash('success', 'You have successfully purchased a ticket.');
    }
    else 
    {
    $this->addFlash('error', 'The tickets are sold out.');
    }
    
    return $this->redirectToRoute('app_ticket_show', ['id' => $ticket->getId()], Response::HTTP_SEE_OTHER);
}

    /**
    * @Route("/buyticketPT/{id}", name="app_buyticket", methods={"GET", "POST"})
    */
public function buyticketPT(Request $request, Ticket $ticket): Response
{   


    $stock = $ticket->getStock();
    $user = $this->getUser();
    $userPoints = $user->getPoints();
    $pricePt = $ticket->getPricePT();
    if($pricePt <= $userPoints)
    {
        if ($stock > 0) {
            $ticket->setStock($stock - 1);
            $user->setPoints($userPoints - $pricePt);
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
            
            // Send Twilio SMS notification
            $sid = 'AC3fcc859c3c9d7ca0f904d5969b8f077b';
            $token = '4408d07f6b27129cf416cb48fcca0d07';
            $from = '+15673714926';
            $to = '+21650205982';
            
            $client = new Client($sid, $token);
            $event = $ticket->getEvent();
            $message = $client->messages->create(
                $to,
                array(
                    'from' => $from,
                    'body' => 'You have successfully purchased a ticket for '.$event->getTitle().' at '.$ticket->getPrice().' dinars.',
                )
            );

            // Pdf Download
            $ticket = $this->getDoctrine()->getRepository(Ticket::class)->find($ticket);

            if (!$ticket) {
                throw $this->createNotFoundException('Ticket not found');
            }

            $event = $ticket->getEvent();

            $pdfOptions = new Dompdf();
            $html = $this->renderView('FrontOffice/ticket/ticketpdf.html.twig', [
                'event' => $event,
                'ticket' => $ticket,
                'achat' => $achat,
            ]);
            $pdfOptions->loadHtml($html);
            $pdfOptions->setPaper('A4', 'portrait');




            $pdfOptions->render();

            $pdfContent = $pdfOptions->output();
            $response = new Response($pdfContent);

            $response->headers->set('Content-Type', 'application/pdf');
            $response->headers->set('Content-Disposition', 'attachment;filename=ticket.pdf');

            return $response; 

            $this->addFlash('success', 'You have successfully purchased a ticket.');
        }
        else 
        {
        $this->addFlash('error', 'The tickets are sold out.');
        }
    }
    else
    {
        $this->addFlash('error', 'please check that you have enough points then try again!');
    }
    
    return $this->redirectToRoute('app_ticket_show', ['id' => $ticket->getId()], Response::HTTP_SEE_OTHER);
}




//#[Route('/{id}', name: 'app_ticket_delete', methods: ['POST'])]
    
   /* #[Route("/{id}/purchases", name:"sum_of_tickets_purchased_by_user")]
     
    public function sumOfTicketsPurchasedByUser(User $user, AchatRepository $achatRepository): Response
    {
        

        return $this->render('FrontOffice/ticket/show.html.twig', [
            'sumOfTickets' => $sumOfTickets,
        ]);
    }*/
 }