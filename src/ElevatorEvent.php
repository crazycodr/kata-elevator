<?php

namespace Kata;

class ElevatorEvent implements Event
{
    private string $eventType;

    public function __construct(string $eventType)
    {
        $this->eventType = $eventType;
    }

    public function getName(): string
    {
        return 'elevator-event';
    }

    public function getEventType(): string
    {
        return $this->eventType;
    }
}
