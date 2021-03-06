<?php

namespace Speakers;

use Kata\Core\EventPipeline;
use Kata\Core\Subscriber;
use Kata\Speakers\ElevatorSpeaker;
use Kata\Speakers\SpeakerEvent;
use Kata\Structure\Elevator\Elevator;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Kata\Speakers\ElevatorSpeakerBeepsOnFloorChangeEventSubscriber
 */
class ElevatorSpeakerBeepsOnFloorChangeEventSubscriberTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        EventPipeline::setInstance(null);
    }

    public function testItEmitsABeepSpeakerEventWhenFloorChanges(): void
    {
        $elevator = new Elevator('el1');
        new ElevatorSpeaker('el1');
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

    public function testItDoesNotEmitABeepSpeakerEventWhenFloorChangesOnDifferentElevator(): void
    {
        $elevator = new Elevator('el1');
        new ElevatorSpeaker('el2');
        $beeped = false;
        $beepSubscriberMock = $this->createMock(Subscriber::class);
        $beepSubscriberMock->method('getEventName')->willReturn('speaker-event');
        $beepSubscriberMock->expects($this->never())->method('respond')->willReturnCallback(function (SpeakerEvent $event) use (&$beeped) {
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
        $this->assertFalse($beeped);
    }
}
