<?php

namespace Kata;

class Speaker
{
    public function __construct()
    {
        EventPipeline::getInstance()->addSubscriber(new SpeakerBeepsOnFloorChangeEventSubscriber());
        EventPipeline::getInstance()->addSubscriber(new SpeakerRingsOnDoorOpeningEventSubscriber());
    }
}
