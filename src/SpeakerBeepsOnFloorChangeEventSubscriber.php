<?php

namespace Kata;

class SpeakerBeepsOnFloorChangeEventSubscriber implements Subscriber
{
    public function getEventName(): string
    {
        return 'elevator-event';
    }

    public function respond(Event $event): void
    {
        /** @var ElevatorEvent $event */
        if ($event->getEventType() === 'changed-floor') {
            EventPipeline::getInstance()->dispatchEvent(new SpeakerEvent('beep'));
        }
    }
}
