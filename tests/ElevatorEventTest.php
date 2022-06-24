<?php

namespace Kata;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Kata\ElevatorEvent
 */
class ElevatorEventTest extends TestCase
{

    public function testCreatingAnElevatorEventSavesTheEventType(): void
    {
        $event = new ElevatorEvent('moving');
        $this->assertEquals('moving', $event->getEventType());
    }

    public function testEventNameDoesNotChange(): void
    {
        $event = new ElevatorEvent('moving');
        $this->assertEquals('elevator-event', $event->getName());
    }
}
