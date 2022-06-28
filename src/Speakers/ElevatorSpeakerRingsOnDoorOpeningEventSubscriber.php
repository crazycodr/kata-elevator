<?php

namespace Kata\Speakers;

use Kata\Core\Event;
use Kata\Core\EventPipeline;
use Kata\Core\Subscriber;
use Kata\Structure\DoorEvent;

class ElevatorSpeakerRingsOnDoorOpeningEventSubscriber implements Subscriber
{
    private string $elevator;

    public function __construct(string $elevator)
    {
        $this->elevator = $elevator;
    }

    public function getEventName(): string
    {
        return 'door-event';
    }

    public function respond(Event $event): void
    {
        /** @var DoorEvent $event */
        if ($event->getEventType() === 'opening' && $event->getElevator() === $this->elevator) {
            EventPipeline::getInstance()->dispatchEvent(new SpeakerEvent('ring'));
        }
    }
}
