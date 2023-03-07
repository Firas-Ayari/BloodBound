<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Google_Client;
use Google_Service_Calendar;

class GoogleApiController extends AbstractController
{
    public function authenticate()
    {
        $client = new Google_Client();
        $client->setAuthConfig('path/to/client_secret.json');
        $client->addScope(Google_Service_Calendar::CALENDAR);
        $client
    }

    public function calendar()
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
    } else {
        return new RedirectResponse($client->createAuthUrl());
    }

    return $this->render('google_api/calendar.html.twig', array(
        'eventList' => $eventList
    ));
}

}