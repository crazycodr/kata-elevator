<?php

namespace Speakers;

use Kata\Core\EventPipeline;
use Kata\Speakers\FloorSpeaker;
use Kata\Speakers\FloorSpeakerRingsOnDoorOpeningEventSubscriber;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Kata\Speakers\FloorSpeaker
 */
class FloorSpeakerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        EventPipeline::setInstance(null);
    }

    public function testSpeakerRegistersADoorEventSubscriber(): void
    {
        new FloorSpeaker(12);
        $found = false;
        foreach (EventPipeline::getInstance()->getSubscribers() as $subscriber) {
            $found = $found || $subscriber instanceof FloorSpeakerRingsOnDoorOpeningEventSubscriber;
        }
        $this->assertTrue($found);
    }

    public function testFloorIdIsSaved(): void
    {
        $speaker = new FloorSpeaker(11);
        $this->assertEquals(11, $speaker->getFloor());
    }
}
