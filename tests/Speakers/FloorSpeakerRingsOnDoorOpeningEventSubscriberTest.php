<?php

namespace Speakers;

use Kata\Core\EventPipeline;
use Kata\Core\Subscriber;
use Kata\Speakers\ElevatorSpeaker;
use Kata\Speakers\FloorSpeaker;
use Kata\Speakers\SpeakerEvent;
use Kata\Structure\Elevator\Elevator;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Kata\Speakers\FloorSpeakerRingsOnDoorOpeningEventSubscriber
 */
class FloorSpeakerRingsOnDoorOpeningEventSubscriberTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        EventPipeline::setInstance(null);
    }

    public function testItEmitsARingSpeakerEventWhenDoorOpens(): void
    {
        $elevator = new Elevator('el1');
        new FloorSpeaker(1);
        $ringed = false;
        $ringSubscriberMock = $this->createMock(Subscriber::class);
        $ringSubscriberMock->method('getEventName')->willReturn('speaker-event');
        $ringSubscriberMock->expects($this->atLeastOnce())->method('respond')->willReturnCallback(function (SpeakerEvent $event) use (&$ringed) {
            if ($event->getSoundType() === 'ring') {
                $ringed = true;
            }
            return $ringed;
        });
        EventPipeline::getInstance()->addSubscriber($ringSubscriberMock);
        $elevator->move(1);
        $elevator->act();
        $elevator->act();
        $elevator->act();
        $elevator->act();
        $this->assertTrue($ringed);
    }

    public function testItDoesNotEmitARingSpeakerEventWhenOtherDoorOpens(): void
    {
        $elevator = new Elevator('el1');
        new ElevatorSpeaker('el2');
        $ringed = false;
        $beepSubscriberMock = $this->createMock(Subscriber::class);
        $beepSubscriberMock->method('getEventName')->willReturn('speaker-event');
        $beepSubscriberMock->expects($this->never())->method('respond')->willReturnCallback(function (SpeakerEvent $event) use (&$ringed) {
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
        $this->assertFalse($ringed);
    }
}
