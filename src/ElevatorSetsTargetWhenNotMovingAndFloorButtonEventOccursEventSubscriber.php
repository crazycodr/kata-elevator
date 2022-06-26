<?php

namespace Kata;

class ElevatorSetsTargetWhenNotMovingAndFloorButtonEventOccursEventSubscriber implements Subscriber
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
        $this->elevator->move($event->getFloorNumber());
    }
}
