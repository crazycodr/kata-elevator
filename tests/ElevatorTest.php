<?php

namespace Kata;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Kata\Elevator
 */
class ElevatorTest extends TestCase
{
    public function testNewElevatorStartsAtFloor0()
    {
        $elevator = new Elevator();
        $this->assertEquals(0, $elevator->getCurrentFloor());
    }

    public function testNewElevatorHasNoTarget()
    {
        $elevator = new Elevator();
        $this->assertNull($elevator->getTargetFloor());
    }

    public function testNewElevatorIsWaiting()
    {
        $elevator = new Elevator();
        $this->assertEquals(Elevator::STATE_WAITING, $elevator->getCurrentState());
    }

    public function testNewElevatorHasNoDirection()
    {
        $elevator = new Elevator();
        $this->assertEquals(Elevator::DIRECTION_NONE, $elevator->getCurrentDirection());
    }

    public function testMoveSetsTheProperTargetFloor()
    {
        $elevator = new Elevator();
        $elevator->move(6);
        $this->assertEquals(6, $elevator->getTargetFloor());
    }

    public function testMoveDoesntChangeTargetIfAlreadyFulfilling()
    {
        $elevator = new Elevator();
        $elevator->move(6);
        $elevator->move(10);
        $this->assertNotEquals(10, $elevator->getTargetFloor());
    }

    public function testActWillNotChangeAnythingIfThereIsNoTarget()
    {
        $elevator = new Elevator();
        $currentFloor = $elevator->getCurrentFloor();
        $currentDirection = $elevator->getCurrentDirection();
        $currentState = $elevator->getCurrentState();
        $elevator->act();
        $this->assertEquals($currentFloor, $elevator->getCurrentFloor());
        $this->assertEquals($currentDirection, $elevator->getCurrentDirection());
        $this->assertEquals($currentState, $elevator->getCurrentState());
    }

    public function testActWillSetToMovingIfTargetIsSet()
    {
        $elevator = new Elevator();
        $this->assertEquals(Elevator::STATE_WAITING, $elevator->getCurrentState());
        $elevator->move(3);
        $elevator->act();
        $this->assertEquals(Elevator::STATE_MOVING, $elevator->getCurrentState());
    }

    public function testActWillSetDirectionUpWhenMovingToFloorThatIsHigherThanCurrentFloor()
    {
        $elevator = new Elevator();
        $this->assertEquals(Elevator::DIRECTION_NONE, $elevator->getCurrentDirection());
        $elevator->move(3);
        $elevator->act();
        $this->assertEquals(Elevator::DIRECTION_UP, $elevator->getCurrentDirection());
    }

    public function testActWillSetDirectionDownWhenMovingToFloorThatIsLowerThanCurrentFloor()
    {
        $elevator = new Elevator();
        $this->assertEquals(Elevator::DIRECTION_NONE, $elevator->getCurrentDirection());
        $elevator->move(-3);
        $elevator->act();
        $this->assertEquals(Elevator::DIRECTION_DOWN, $elevator->getCurrentDirection());
    }

    public function testActWillMoveElevatorUpWhenStateIsMovingAndDirectionIsUp()
    {
        $elevator = new Elevator();
        $this->assertEquals(Elevator::DIRECTION_NONE, $elevator->getCurrentDirection());
        $this->assertEquals(Elevator::STATE_WAITING, $elevator->getCurrentState());
        $this->assertEquals(0, $elevator->getCurrentFloor());
        $elevator->move(3);
        $elevator->act();
        $this->assertEquals(Elevator::DIRECTION_UP, $elevator->getCurrentDirection());
        $this->assertEquals(Elevator::STATE_MOVING, $elevator->getCurrentState());
        $this->assertEquals(1, $elevator->getCurrentFloor());
    }

    public function testActWillMoveElevatorDownWhenStateIsMovingAndDirectionIsDown()
    {
        $elevator = new Elevator();
        $this->assertEquals(Elevator::DIRECTION_NONE, $elevator->getCurrentDirection());
        $this->assertEquals(Elevator::STATE_WAITING, $elevator->getCurrentState());
        $this->assertEquals(0, $elevator->getCurrentFloor());
        $elevator->move(-3);
        $elevator->act();
        $this->assertEquals(Elevator::DIRECTION_DOWN, $elevator->getCurrentDirection());
        $this->assertEquals(Elevator::STATE_MOVING, $elevator->getCurrentState());
        $this->assertEquals(-1, $elevator->getCurrentFloor());
    }

    public function testActWillResetTheTargetFloorWhenReachingTarget()
    {
        $elevator = new Elevator();
        $elevator->move(2);
        $elevator->act();
        $elevator->act();
        $elevator->act();
        $this->assertNull($elevator->getTargetFloor());
    }

    public function testElevatorTriggersElevatorEventWhenChangingFloor():void
    {
        $eventPipeline = EventPipeline::getInstance();
        $subscriber = $this->getElevatorEventSubscriberMock();
        $subscriber->expects($this->exactly(2))->method('respond')->willReturnCallback(function (ElevatorEvent $event) {
            $this->assertEquals('changed-floor', $event->getEventType());
        });
        $eventPipeline->addSubscriber($subscriber);
        $elevator = new Elevator();
        $elevator->move(2);
        $elevator->act();
        $elevator->act();
    }

    public function getElevatorEventSubscriberMock(): Subscriber|MockObject
    {
        $mock = $this->createMock(Subscriber::class);
        $mock->method('getEventName')->willReturn('elevator-event');
        return $mock;
    }

    public function testElevatorTriggersDoorEventsWhenMovingDoors():void
    {
        $eventPipeline = EventPipeline::getInstance();
        $subscriber = $this->getDoorEventSubscriberMock();
        $subscriber->expects($this->exactly(4))->method('respond')->willReturnCallback(function (DoorEvent $event) {
            $this->logicalOr(
                $this->equalTo('opening'),
                $this->equalTo('open'),
                $this->equalTo('closing'),
                $this->equalTo('closed')
            )->matches($event->getEventType());
        });
        $eventPipeline->addSubscriber($subscriber);
        $elevator = new Elevator();
        $elevator->move(2);
        $elevator->act();
        $elevator->act();
        $elevator->act();
        $elevator->act();
        $elevator->act();
        $elevator->act();
    }

    public function getDoorEventSubscriberMock(): Subscriber|MockObject
    {
        $mock = $this->createMock(Subscriber::class);
        $mock->method('getEventName')->willReturn('door-event');
        return $mock;
    }
}
