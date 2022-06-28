<?php

namespace Kata\Structure\Elevator;

use Kata\Core\Event;
use Kata\Core\Subscriber;

class ElevatorMovesWhenTimeIsTickingEventSubscriber implements Subscriber
{
    private Elevator $elevator;

    public function __construct(Elevator $elevator)
    {
        $this->elevator = $elevator;
    }

    public function getEventName(): string
    {
        return 'tick';
    }

    public function respond(Event $event): void
    {
        $this->elevator->act();
    }
}
