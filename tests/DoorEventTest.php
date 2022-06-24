<?php

namespace Kata;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Kata\DoorEvent
 */
class DoorEventTest extends TestCase
{
    public function testCreatingADoorEventSavesTheEventType(): void
    {
        $event = new DoorEvent('opening');
        $this->assertEquals('opening', $event->getEventType());
    }

    public function testEventNameDoesNotChange(): void
    {
        $event = new DoorEvent('moving');
        $this->assertEquals('door-event', $event->getName());
    }
}
