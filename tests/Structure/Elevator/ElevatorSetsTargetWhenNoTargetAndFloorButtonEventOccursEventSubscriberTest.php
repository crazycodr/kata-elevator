<?php

namespace Structure\Elevator;

use Kata\Core\EventPipeline;
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
        $elevator->expects($this->once())->method('move')->with($this->anything());
        $elevator->move(5);
        $subscriber = new ElevatorSetsTargetWhenNoTargetAndFloorButtonEventOccursEventSubscriber($elevator);
        $subscriber->respond(new FloorButtonEvent(11));
        $this->assertEquals(5, $elevator->getTargetFloor());
    }

    public function testSubscriberAbandonsIfAnotherSubscriberFulfilledTheRequest(): void
    {
        $elevator1 = $this->getMockBuilder(Elevator::class)
            ->setConstructorArgs(['el1'])
            ->enableProxyingToOriginalMethods()
            ->getMock();
        $elevator1->expects($this->once())->method('move')->with($this->anything());
        $elevator2 = $this->getMockBuilder(Elevator::class)
            ->setConstructorArgs(['el2'])
            ->enableProxyingToOriginalMethods()
            ->getMock();
        $elevator2->expects($this->never())->method('move')->with($this->anything());
        EventPipeline::getInstance()->addSubscriber(new ElevatorSetsTargetWhenNoTargetAndFloorButtonEventOccursEventSubscriber($elevator1));
        EventPipeline::getInstance()->addSubscriber(new ElevatorSetsTargetWhenNoTargetAndFloorButtonEventOccursEventSubscriber($elevator2));
        EventPipeline::getInstance()->dispatchEvent(new FloorButtonEvent(11));
    }

    public function testSubscriberForAlreadyMovingElevatorWorksForNextSubscriber(): void
    {
        $elevator1 = $this->getMockBuilder(Elevator::class)
            ->setConstructorArgs(['el1'])
            ->enableProxyingToOriginalMethods()
            ->getMock();
        $elevator1->expects($this->once())->method('move')->with(5);
        $elevator1->move(5);
        $elevator2 = $this->getMockBuilder(Elevator::class)
            ->setConstructorArgs(['el2'])
            ->enableProxyingToOriginalMethods()
            ->getMock();
        $elevator2->expects($this->once())->method('move')->with(11);
        EventPipeline::getInstance()->addSubscriber(new ElevatorSetsTargetWhenNoTargetAndFloorButtonEventOccursEventSubscriber($elevator1));
        EventPipeline::getInstance()->addSubscriber(new ElevatorSetsTargetWhenNoTargetAndFloorButtonEventOccursEventSubscriber($elevator2));
        EventPipeline::getInstance()->dispatchEvent(new FloorButtonEvent(11));
    }
}
