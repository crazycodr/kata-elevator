<?php

namespace Kata;

class ElevatorDisplay
{
    private string $elevator;
    private int $floor = 0;

    public function __construct(string $elevator)
    {
        $this->elevator = $elevator;
        EventPipeline::getInstance()->addSubscriber(new ElevatorDisplayUpdatesWhenFloorChangeEventSubscriber($this, $this->elevator));
    }

    public function setFloor(int $floor): void
    {
        $this->floor = $floor;
    }

    public function getFloor(): int
    {
        return $this->floor;
    }

    /**
     * @return string
     */
    public function getElevator(): string
    {
        return $this->elevator;
    }
}
