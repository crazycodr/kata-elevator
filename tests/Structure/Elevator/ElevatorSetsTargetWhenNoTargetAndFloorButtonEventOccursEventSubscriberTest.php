<?php

namespace Structure\Elevator;

use Kata\Structure\Elevator\Elevator;
use Kata\Structure\Elevator\ElevatorSetsTargetWhenNoTargetAndFloorButtonEventOccursEventSubscriber;
use Kata\Structure\Floor\FloorButtonEvent;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Kata\Structure\Elevator\ElevatorSetsTargetWhenNoTargetAndFloorButtonEventOccursEventSubscriber
 */
class ElevatorSetsTargetWhenNoTargetAndFloorButtonEventOccursEventSubscriberTest extends TestCase
{
    public function testSubscriberListensToFloorButtonEvents(): void
    {
        $elevator = new Elevator('el1');
        $subscriber = new ElevatorSetsTargetWhenNoTargetAndFloorButtonEventOccursEventSubscriber($elevator);
        $this->assertEquals('floor-button-event', $subscriber->getEventName());
    }

    public function testSubscriberMovesElevator(): void
    {
        $elevator = new Elevator('el1');
        $subscriber = new ElevatorSetsTargetWhenNoTargetAndFloorButtonEventOccursEventSubscriber($elevator);
        $subscriber->respond(new FloorButtonEvent(11));
        $this->assertEquals(11, $elevator->getTargetFloor());
    }

    public function testSubscriberMovesElevatorOnlyIfNotAlreadyMoving(): void
    {
        $elevator = $this->getMockBuilder(Elevator::class)
            ->setConstructorArgs(['el1'])
            ->enableProxyingToOriginalMethods()
            ->getMock();
        $elevator->method('getId')->willReturn('el1');
        $elevator->expects($this->once())->method('move')->with($this->anything());
        $elevator->move(5);
        $subscriber = new ElevatorSetsTargetWhenNoTargetAndFloorButtonEventOccursEventSubscriber($elevator);
        $subscriber->respond(new FloorButtonEvent(11));
        $this->assertEquals(5, $elevator->getTargetFloor());
    }
}
