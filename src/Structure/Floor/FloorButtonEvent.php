<?php

namespace Kata\Structure\Floor;

use Kata\Core\Event;

class FloorButtonEvent implements Event
{
    private int $floorNumber;

    public function __construct(int $floorNumber)
    {
        $this->floorNumber = $floorNumber;
    }

    public function getName(): string
    {
        return 'floor-button-event';
    }

    /**
     * @return int
     */
    public function getFloorNumber(): int
    {
        return $this->floorNumber;
    }
}
