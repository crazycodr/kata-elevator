<?php

namespace Kata;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Kata\SpeakerBeepsOnFloorChangeEventSubscriber
 */
class SpeakerBeepsOnFloorChangeEventSubscriberTest extends TestCase
{
    public function testItEmitsABeepSpeakerEventWhenFloorChanges(): void
    {
        $elevator = new Elevator();
        new Speaker();
        $beeped = false;
        $beepSubscriberMock = $this->createMock(Subscriber::class);
        $beepSubscriberMock->method('getEventName')->willReturn('speaker-event');
        $beepSubscriberMock->expects($this->atLeastOnce())->method('respond')->willReturnCallback(function (SpeakerEvent $event) use (&$beeped) {
            if ($event->getSoundType() === 'beep') {
                $beeped = true;
            }
            return $beeped;
        });
        EventPipeline::getInstance()->addSubscriber($beepSubscriberMock);
        $elevator->move(1);
        $elevator->act();
        $elevator->act();
        $elevator->act();
        $elevator->act();
        $this->assertTrue($beeped);
    }
}
