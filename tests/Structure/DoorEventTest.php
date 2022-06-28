<?php

namespace Structure;

use Kata\Structure\DoorEvent;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Kata\Structure\DoorEvent
 */
class DoorEventTest extends TestCase
{
    public function testCreatingADoorEventSavesTheEventType(): void
    {
        $event = new DoorEvent('opening', 'el1', 1);
        $this->assertEquals('opening', $event->getEventType());
    }

    public function testEventNameDoesNotChange(): void
    {
        $event = new DoorEvent('moving', 'el1', 1);
        $this->assertEquals('door-event', $event->getName());
    }

    public function testElevatorIdIsSaved(): void
    {
        $event = new DoorEvent('moving', 'el2', 1);
        $this->assertEquals('el2', $event->getElevator());
    }

    public function testFloorIdIsSaved(): void
    {
        $event = new DoorEvent('moving', 'el2', 2);
        $this->assertEquals(2, $event->getFloor());
    }
}
