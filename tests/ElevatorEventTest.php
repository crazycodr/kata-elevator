<?php

use Kata\ElevatorFloorChangedEvent;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Kata\ElevatorFloorChangedEvent
 */
class ElevatorEventTest extends TestCase
{
    public function testCreatingAnElevatorEventSavesTheEventType(): void
    {
        $event = new ElevatorFloorChangedEvent('moving', 'el1', 1);
        $this->assertEquals('moving', $event->getEventType());
    }

    public function testEventNameDoesNotChange(): void
    {
        $event = new ElevatorFloorChangedEvent('moving', 'el1', 1);
        $this->assertEquals('elevator-event', $event->getName());
    }

    public function testElevatorIdIsSaved(): void
    {
        $event = new ElevatorFloorChangedEvent('moving', 'el2', 1);
        $this->assertEquals('el2', $event->getElevator());
    }

    public function testElevatorFloorIsSaved(): void
    {
        $event = new ElevatorFloorChangedEvent('moving', 'el2', 11);
        $this->assertEquals(11, $event->getFloor());
    }
}
