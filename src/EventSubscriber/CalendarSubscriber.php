<?php

namespace App\EventSubscriber;

use App\Repository\BookingRepository;
use CalendarBundle\CalendarEvents;
use CalendarBundle\Entity\Event;
use CalendarBundle\Event\CalendarEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CalendarSubscriber implements EventSubscriberInterface
{
    private $bookingRepository;
    private $router;

    public function __construct(
        BookingRepository $bookingRepository,
        UrlGeneratorInterface $router
    ) {
        $this->bookingRepository = $bookingRepository;
        $this->router = $router;
    }

    public static function getSubscribedEvents()
    {
        return [
            CalendarEvents::SET_DATA => 'onCalendarSetData',
        ];
    }

    public function onCalendarSetData(CalendarEvent $calendar)
    {
        $start = $calendar->getStart();
        $end = $calendar->getEnd();
        $userid=null;
        $filters = $calendar->getFilters();
        if ($filters != null){
          $userid = $filters['user'];
        }            
        if ($userid == -1) {
        // Modify the query to fit to your entity and needs
        // Change booking.beginAt by your start date property
        $bookings = $this->bookingRepository
            ->createQueryBuilder('booking')
            ->where('booking.beginAt BETWEEN :start and :end OR booking.endAt BETWEEN :start and :end')
            ->setParameter('start', $start->format('Y-m-d H:i:s'))
            ->setParameter('end', $end->format('Y-m-d H:i:s'))
            ->getQuery()
            ->getResult()
        ;
        } else {
            $bookings = $this->bookingRepository
            ->createQueryBuilder('booking')
            ->where('booking.beginAt BETWEEN :start and :end OR booking.endAt BETWEEN :start and :end')
            ->setParameter('start', $start->format('Y-m-d H:i:s'))
            ->setParameter('end', $end->format('Y-m-d H:i:s'))
            ->andWhere('booking.formateur = :userid')
            ->setParameter('userid', $userid)
            ->getQuery()
            ->getResult()
            ;
        }

        foreach ($bookings as $booking) {
            // this create the events with your data (here booking data) to fill calendar
            $bookingEvent = new Event(
                $booking->getTitle(),
                $booking->getBeginAt(),
                $booking->getEndAt() // If the end date is null or not defined, a all day event is created.
            );

            /*
             * Add custom options to events
             *
             * For more information see: https://fullcalendar.io/docs/event-object
             * and: https://github.com/fullcalendar/fullcalendar/blob/master/src/core/options.ts
             */
            
            if ($userid == -1) {
                $journee = ($booking->isMatin()?($booking->isAprem()? 'Matin + Après-Midi': 'Matin') : 'Après-Midi');

                $bookingEvent->setOptions([
                'backgroundColor' => $booking->getFormateur()->getCouleur(),
                'borderColor' => $booking->getFormateur()->getCouleur(),
                'title' => ' ' . $booking->getCours() .' - '. $booking->getPromo() .' '. $booking->getTitle() .' - '. $booking->getCentre() .' - '. $journee,
                'formateur' => $booking->getFormateur()->getPrenom() . ' ' . $booking->getFormateur()->getNom(),
                'matin' => $booking->isMatin(),
                'aprem' => $booking->isAprem(),
                'textColor' => '#000000'
                ]);
                
                $bookingEvent->addOption(
                    'url',
                    $this->router->generate('app_booking_edit', [
                        'id' => $booking->getId(),
                    ])
                );
            } else{
                $journee = ($booking->isMatin()?($booking->isAprem()? 'Matin + Après-Midi': 'Matin') : 'Après-Midi');

                $bookingEvent->setOptions([
                    'backgroundColor' => $booking->getCentre()->getCouleur(),
                    'borderColor' => $booking->getCentre()->getCouleur(),
                    'title' => ' ' . $booking->getCours() .' - '. $booking->getPromo() .' '. $booking->getTitle() .' - '. $booking->getCentre() .' - '. $journee,
                    'formateur' => $booking->getFormateur()->getPrenom() . ' ' . $booking->getFormateur()->getNom(),
                    'matin' => $booking->isMatin(),
                    'aprem' => $booking->isAprem(),
                    ]);
                    
                    $bookingEvent->addOption(
                        'url',
                        $this->router->generate('app_booking_show', [
                            'id' => $booking->getId(),
                        ])
                    );
            }

            // finally, add the event to the CalendarEvent to fill the calendar
            $calendar->addEvent($bookingEvent);
        }
    }
}