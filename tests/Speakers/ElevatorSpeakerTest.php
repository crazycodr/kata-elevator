<?php

namespace Speakers;

use Kata\Core\EventPipeline;
use Kata\Speakers\ElevatorSpeaker;
use Kata\Speakers\ElevatorSpeakerBeepsOnFloorChangeEventSubscriber;
use Kata\Speakers\ElevatorSpeakerRingsOnDoorOpeningEventSubscriber;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Kata\Speakers\ElevatorSpeaker
 */
class ElevatorSpeakerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        EventPipeline::setInstance(null);
    }

    public function testSpeakerRegistersAFloorChangeSubscriber(): void
    {
        new ElevatorSpeaker('el1');
        $found = false;
        foreach (EventPipeline::getInstance()->getSubscribers() as $subscriber) {
            $found = $found || $subscriber instanceof ElevatorSpeakerBeepsOnFloorChangeEventSubscriber;
        }
        $this->assertTrue($found);
    }

    public function testSpeakerRegistersADoorEventSubscriber(): void
    {
        new ElevatorSpeaker('el1');
        $found = false;
        foreach (EventPipeline::getInstance()->getSubscribers() as $subscriber) {
            $found = $found || $subscriber instanceof ElevatorSpeakerRingsOnDoorOpeningEventSubscriber;
        }
        $this->assertTrue($found);
    }

    public function testElevatorIdIsSaved(): void
    {
        $speaker = new ElevatorSpeaker('el2');
        $this->assertEquals('el2', $speaker->getElevator());
    }
}
