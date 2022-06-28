<?php

namespace Kata\Speakers;

use Kata\Core\EventPipeline;

class ElevatorSpeaker
{
    private string $elevator;

    public function __construct(string $elevator)
    {
        EventPipeline::getInstance()->addSubscriber(new ElevatorSpeakerBeepsOnFloorChangeEventSubscriber($elevator));
        EventPipeline::getInstance()->addSubscriber(new ElevatorSpeakerRingsOnDoorOpeningEventSubscriber($elevator));
        $this->elevator = $elevator;
    }

    /**
     * @return string
     */
    public function getElevator(): string
    {
        return $this->elevator;
    }
}
