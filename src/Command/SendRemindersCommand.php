<?php

namespace App\Command;

use App\Entity\Event;
use App\Entity\Ticket;
use App\Entity\User;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\UX\Notify\Notification;
use Symfony\UX\Notify\NotifierInterface;

#[AsCommand(
    name: 'send-reminders',
    description: 'Add a short description for your command',
)]
class SendRemindersCommand extends Command
{
    protected function configure()
    {
        $this
            ->setDescription('Send reminders to users who purchased tickets for events happening today');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Fetch all the events whose date is equal to the current date and time
        $events = $this->getDoctrine()->getRepository(Event::class)->createQueryBuilder('e')
            ->where('e.eventDate = :eventDate')
            ->setParameter('eventDate', new \DateTime())
            ->getQuery()
            ->getResult();

        // For each event, find all the users who purchased tickets for that event
        foreach ($events as $event) {
            $tickets = $this->getDoctrine()->getRepository(Ticket::class)->findBy(['event_id' => $event->getId()]);
            foreach ($tickets as $ticket) {
                $user = $this->getDoctrine()->getRepository(User::class)->find($ticket->getUser_id());

                // Send a notification to the user using Symfony UX Notify
                $notification = new Notification('Reminder', [
                    'type' => 'success',
                    //'icon' => 'fas fa-bell',
                    'timeout' => 10000,
                ]);
                $notification->content('Reminder for event '.$event->getEvent_id());
                $notification->to($user);

                $this->get('notify')->notify($notification);
                
            }
        }

        $output->writeln('Reminders sent successfully.');

        return Command::SUCCESS;
        
        //$io = new SymfonyStyle($input, $output);
        //$arg1 = $input->getArgument('arg1');

        //if ($arg1) {
        //    $io->note(sprintf('You passed an argument: %s', $arg1));
        //}

       // if ($input->getOption('option1')) {
            // ...
       // }

       // $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        //return Command::SUCCESS;
    }
}
