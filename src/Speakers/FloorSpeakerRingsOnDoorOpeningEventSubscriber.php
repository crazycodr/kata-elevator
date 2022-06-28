<?php

namespace Kata\Speakers;

use Kata\Core\Event;
use Kata\Core\EventPipeline;
use Kata\Core\Subscriber;
use Kata\Structure\DoorEvent;

class FloorSpeakerRingsOnDoorOpeningEventSubscriber implements Subscriber
{
    private int $floor;

    public function __construct(int $floor)
    {
        $this->floor = $floor;
    }

    public function getEventName(): string
    {
        return 'door-event';
    }

    public function respond(Event $event): void
    {
        /** @var DoorEvent $event */
        if ($event->getEventType() === 'opening' && $event->getFloor() === $this->floor) {
            EventPipeline::getInstance()->dispatchEvent(new SpeakerEvent('ring'));
        }
    }
}
