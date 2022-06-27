<?php

namespace Kata;

class ElevatorFloorChangedEvent implements Event
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
        return 'elevator-event';
    }

    public function getEventType(): string
    {
        return $this->eventType;
    }

    /**
     * @return string
     */
    public function getElevator(): string
    {
        return $this->elevator;
    }

    /**
     * @return int
     */
    public function getFloor(): int
    {
        return $this->floor;
    }
}
