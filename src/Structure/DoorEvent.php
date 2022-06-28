<?php

namespace Kata\Structure;

use Kata\Core\Event;

class DoorEvent implements Event
{
    private string $eventType;
    private string $elevator;
    private int $floor;

    public function __construct(string $eventType, string $elevator, int $floor)
    {
        $this->eventType = $eventType;
        $this->elevator = $elevator;
        $this->floor = $floor;
    }

    public function getName(): string
    {
        return 'door-event';
    }

    public function getEventType(): string
    {
        return $this->eventType;
    }

    public function getElevator(): string
    {
        return $this->elevator;
    }

    public function getFloor(): ?int
    {
        return $this->floor;
    }
}
