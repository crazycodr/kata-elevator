<?php

namespace Kata;

class DoorEvent implements Event
{
    private string $eventType;

    public function __construct(string $eventType)
    {
        $this->eventType = $eventType;
    }

    public function getName(): string
    {
        return 'door-event';
    }

    public function getEventType(): string
    {
        return $this->eventType;
    }
}
