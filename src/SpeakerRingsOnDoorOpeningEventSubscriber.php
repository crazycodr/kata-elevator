<?php

namespace Kata;

class SpeakerRingsOnDoorOpeningEventSubscriber implements Subscriber
{
    public function getEventName(): string
    {
        return 'door-event';
    }

    public function respond(Event $event): void
    {
        /** @var DoorEvent $event */
        if ($event->getEventType() === 'opening') {
            EventPipeline::getInstance()->dispatchEvent(new SpeakerEvent('ring'));
        }
    }
}
