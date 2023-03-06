<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Ticket;
use App\Entity\User;
use App\Form\EventType;
use App\Repository\EventRepository;
use App\Repository\AchatRepository;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Notifier\NotifierInterface; // hedhy zedtha ena
//use Symfony\UX\Notify\NotifierInterface;
use Symfony\UX\Notify\Notification; //Maybe ghalta  use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


#[Route('/event')]
class EventController extends AbstractController
{
    private $slugger;
    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }
    #[Route('/admin', name: 'app_event_index_admin', methods: ['GET'])]
    public function indexAdmin(EventRepository $eventRepository): Response
    {
        // Statistics Event Status
        $completeEvents = $this->getDoctrine()->getRepository(Event::class)->count(['status' => 'complet']);
        $incompleteEvents = $this->getDoctrine()->getRepository(Event::class)->count(['status' => 'non complet']);
        
        
        return $this->render('BackOffice/event/index.html.twig', [
            'events' => $eventRepository->findAll(),
            'completeEvents' => $completeEvents,
            'incompleteEvents' => $incompleteEvents,
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
            

            //upload img
            $image = $form->get('image')->getData();

            // this condition is needed because the 'image' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $this->slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();

                // Move the file to the directory where images are stored
                try {
                    $image->move(
                        $this->getParameter('img_directory'),
                        $newFilename
                    );

                    
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'imageename' property to store img name
                // instead of its contents
                $event->setImage($newFilename);
                $eventRepository->save($event, true);
               
                
            }


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
        $tickets = $event->getTicket();
        return $this->render('BackOffice/event/show.html.twig', [
            'event' => $event,
            'tickets' => $tickets
        ]);
    }

    #[Route('/{id}', name: 'app_event_show', methods: ['GET'])]
    public function show(Event $event): Response
    {
        return $this->render('FrontOffice/event/show.html.twig', [
            'event' => $event,
        ]);
    }
    #[Route('/{id}/ticket', name: 'app_event_show_ticket', methods: ['GET'])]
    public function showTicketById(Event $event, AchatRepository $achatRepository): Response
    {
        $ticket = $event->getTicket();
        $count = $achatRepository->countTicketsPurchasedForTicketId($ticket);
        return $this->render('FrontOffice/ticket/show.html.twig', [
            'ticket' => $ticket,
            'count' => $count
        ]);
    }
   
   // #[Route('/{id}/tickets', name: 'app_event_show', methods: ['GET'])]
   // public function showTickets(Event $event): Response
   // {
   //     $tickets = $event->getTickets();
   //     return $this->render('FrontOffice/event/show.html.twig', [
   //         'event' => $event,
   //         'tickets' => $tickets
   //     ]);
   // }

    #[Route('/admin/{id}/edit', name: 'app_event_editAdmin', methods: ['GET', 'POST'])]
    public function editAdmin(Request $request, Event $event, EventRepository $eventRepository): Response
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();

            // this condition is needed because the 'image' field is not required
            
            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $this->slugger->slug($originalFilename); // slugger gives the unique name to the image 
                $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();

                // Move the file to the directory where images are stored
                try {
                    $image->move(
                        $this->getParameter('img_directory'),
                        $newFilename
                    );

                    
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'imageename' property to store img name
                // instead of its contents
                $event->setImage($newFilename);
                $eventRepository->save($event, true);

            return $this->redirectToRoute('app_event_index_admin', [], Response::HTTP_SEE_OTHER);
        }

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

   /* public function vote(Article $article, int $value): Response
    {
        // Check if the user has already voted on this article
        $vote = $this->getDoctrine()
            ->getRepository(Vote::class)
            ->findOneBy([
                'article' => $article,
                'user' => $this->getUser(),
            ]);
        if ($vote) {
            // User has already voted, update the value of their vote
            $vote->setValue($value);
        } else {
            // User hasn't voted yet, create a new vote
            $vote = new Vote();
            $vote->setArticle($article)
                ->setUser($this->getUser())
                ->setValue($value);
        }
        // Save the vote to the database
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($vote);
        $entityManager->flush();
        return $this->redirectToRoute('app_article_showfront', ['id' => $article->getId()]);
    } */

   
   
   
   
    // private $notifier;

  //  public function __construct(NotifierInterface $notifier) //NotifierInterface Class is not defined
  //  {
 //       $this->notifier = $notifier;
  //  }

   // public function notify(Notification $notification)
   // {
        /*$notification = new Notification($message, [
            'type' => $type,
            'icon' => $icon,
            'timeout' => $timeout,
        ]);*/

       // $this->notifier->send($notification);
  //  }
}

//class SendRemindersCommand extends Command
//{
  //  protected static $defaultName = 'app:send-reminders';

//}