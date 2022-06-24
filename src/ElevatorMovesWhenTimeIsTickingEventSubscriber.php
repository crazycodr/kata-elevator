<?php

namespace Kata;

class ElevatorMovesWhenTimeIsTickingEventSubscriber implements Subscriber
{
    private Elevator $elevator;

    public function __construct(Elevator $elevator)
    {
        $this->elevator = $elevator;
    }

    function getEventName(): string
    {
        return 'tick';
    }

    function respond(Event $event): void
    {
        $this->elevator->act();
    }
}