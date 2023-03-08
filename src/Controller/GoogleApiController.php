<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Google_Client;
use Google_Service_Calendar;
use Symfony\Component\HttpFoundation\RedirectResponse;

class GoogleApiController extends AbstractController
{
   #[Route("/authenticate", name:"google_authenticate")]
    public function authenticate()
    {
        $client = new Google_Client();
        $client->setAuthConfig('path/to/client_secret.json');
        $client->addScope(Google_Service_Calendar::CALENDAR);
        $client->setRedirectUri('http://localhost:8000/calendar');

        $google_auth_url = $client->createAuthUrl();
        return new RedirectResponse($google_auth_url);
    }

    #[Route("/planning", name:"planning")]
    public function planning()
    {
        $client = new Google_Client();
        $client->setAuthConfig('path/to/client_secret.json');
        $client->addScope(Google_Service_Calendar::CALENDAR);
        $client->setRedirectUri('http://localhost:8000/calendar');

        if ($this->getUser()) {
            $client->setAccessToken($this->getUser()->getAccessToken());
            $calendar = new Google_Service_Calendar($client);
            $events = $calendar->events->listEvents('primary');
            $eventList = array();
            foreach ($events->getItems() as $event) {
                $start = $event->start->dateTime;
                if (empty($start)) {
                    $start = $event->start->date;
                }
                $eventList[] = array(
                    'id' => $event->getId(),
                    'summary' => $event->getSummary(),
                    'start' => $start
                );
            }

            return $this->render('FrontOffice/planning/index.html.twig', array(
                'eventList' => $eventList
            ));
        } else {
            $google_auth_url = $client->createAuthUrl();
            return $this->render('FrontOffice/planning/index.html.twig', array(
                'google_auth_url' => $google_auth_url
            ));
        }
    }

    #[Route("/calendar", name:"google_calendar")]
    public function calendar()
    {
        $client = new Google_Client();
        $client->setAuthConfig('path/to/client_secret.json');
        $client->addScope(Google_Service_Calendar::CALENDAR);
        $client->setRedirectUri('http://localhost:8000/calendar');

        $code = $this->getRequest()->query->get('code');
        if (!empty($code)) {
            $client->authenticate($code);
            $this->getUser()->setAccessToken($client->getAccessToken());
            $this->getDoctrine()->getManager()->flush();
        }

        return new RedirectResponse('/planning');
    }
}
