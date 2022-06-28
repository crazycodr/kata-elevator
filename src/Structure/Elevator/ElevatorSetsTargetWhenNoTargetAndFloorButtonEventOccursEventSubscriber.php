<?php

namespace Kata\Structure\Elevator;

use Kata\Core\Event;
use Kata\Core\Subscriber;
use Kata\Structure\Floor\FloorButtonEvent;

class ElevatorSetsTargetWhenNoTargetAndFloorButtonEventOccursEventSubscriber implements Subscriber
{
    private Elevator $elevator;

    public function __construct(Elevator $elevator)
    {
        $this->elevator = $elevator;
    }

    public function getEventName(): string
    {
        return 'floor-button-event';
    }

    public function respond(Event $event): void
    {
        /** @var FloorButtonEvent $event */
        if ($event->isFulfilled()) {
            return;
        }
        if ($this->elevator->getTargetFloor() !== null) {
            return;
        }
        $this->elevator->move($event->getFloorNumber());
        $event->setFulfilled();
    }
}
