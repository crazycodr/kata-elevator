<?php

namespace Kata;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Kata\Speaker
 */
class SpeakerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        EventPipeline::setInstance(null);
    }

    public function testSpeakerRegistersAFloorChangeSubscriber(): void
    {
        new Speaker();
        $found = false;
        foreach (EventPipeline::getInstance()->getSubscribers() as $subscriber) {
            $found = $found || $subscriber instanceof SpeakerBeepsOnFloorChangeEventSubscriber;
        }
        $this->assertTrue($found);
    }
    public function testSpeakerRegistersADoorEventSubscriber(): void
    {
        new Speaker();
        $found = false;
        foreach (EventPipeline::getInstance()->getSubscribers() as $subscriber) {
            $found = $found || $subscriber instanceof SpeakerRingsOnDoorOpeningEventSubscriber;
        }
        $this->assertTrue($found);
    }
}
