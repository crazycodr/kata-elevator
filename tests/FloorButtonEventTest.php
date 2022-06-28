<?php

use Kata\FloorButtonEvent;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Kata\FloorButtonEvent
 */
class FloorButtonEventTest extends TestCase
{
    public function testEventNameNeverChanges(): void
    {
        $event = new FloorButtonEvent(1);
        $this->assertEquals('floor-button-event', $event->getName());
    }

    public function testFloorNumberIsProperlySaved(): void
    {
        $event = new FloorButtonEvent(17);
        $this->assertEquals(17, $event->getFloorNumber());
    }
}
