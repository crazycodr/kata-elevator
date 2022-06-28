<?php

namespace Structure\Floor;

use Kata\Structure\Floor\FloorButtonEvent;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Kata\Structure\Floor\FloorButtonEvent
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

    public function testIsNotFulfilledByDefault(): void
    {
        $event = new FloorButtonEvent(17);
        $this->assertFalse($event->isFulfilled());
    }

    public function testSettingFulfilledSavesNewStateProperly(): void
    {
        $event = new FloorButtonEvent(17);
        $event->setFulfilled();
        $this->assertTrue($event->isFulfilled());
    }
}
