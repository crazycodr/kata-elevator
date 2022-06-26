<?php

namespace Kata;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Kata\SpeakerRingsOnDoorOpeningEventSubscriber
 */
class SpeakerRingsOnDoorOpeningEventSubscriberTest extends TestCase
{
    public function testItEmitsARingSpeakerEventWhenDoorOpens(): void
    {
        $elevator = new Elevator();
        new Speaker();
        $ringed = false;
        $beepSubscriberMock = $this->createMock(Subscriber::class);
        $beepSubscriberMock->method('getEventName')->willReturn('speaker-event');
        $beepSubscriberMock->expects($this->atLeastOnce())->method('respond')->willReturnCallback(function (SpeakerEvent $event) use (&$ringed) {
            if ($event->getSoundType() === 'ring') {
                $ringed = true;
            }
            return $ringed;
        });
        EventPipeline::getInstance()->addSubscriber($beepSubscriberMock);
        $elevator->move(1);
        $elevator->act();
        $elevator->act();
        $elevator->act();
        $elevator->act();
        $this->assertTrue($ringed);
    }
}
