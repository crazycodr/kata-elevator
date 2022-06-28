<?php

namespace Kata\Speakers;

use Kata\Core\EventPipeline;

class FloorSpeaker
{
    private int $floor;

    public function __construct(int $floor)
    {
        EventPipeline::getInstance()->addSubscriber(new FloorSpeakerRingsOnDoorOpeningEventSubscriber($floor));
        $this->floor = $floor;
    }

    /**
     * @return int
     */
    public function getFloor(): int
    {
        return $this->floor;
    }
}
