<?php

namespace Kata\Speakers;

use Kata\Core\Event;
use Kata\Core\EventPipeline;
use Kata\Core\Subscriber;
use Kata\Structure\Elevator\ElevatorFloorChangedEvent;

class ElevatorSpeakerBeepsOnFloorChangeEventSubscriber implements Subscriber
{
    private string $elevator;

    public function __construct(string $elevator)
    {
        $this->elevator = $elevator;
    }

    public function getEventName(): string
    {
        return 'elevator-event';
    }

    public function respond(Event $event): void
    {
        /** @var ElevatorFloorChangedEvent $event */
        if ($event->getEventType() === 'changed-floor' && $event->getElevator() === $this->elevator) {
            EventPipeline::getInstance()->dispatchEvent(new SpeakerEvent('beep'));
        }
    }
}
