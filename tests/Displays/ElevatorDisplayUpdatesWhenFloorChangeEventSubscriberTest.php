<?php

namespace Displays;

use Kata\Displays\ElevatorDisplay;
use Kata\Displays\ElevatorDisplayUpdatesWhenFloorChangeEventSubscriber;
use Kata\Structure\Elevator\Elevator;
use Kata\Structure\Elevator\ElevatorFloorChangedEvent;
use Kata\Structure\Floor\Floor;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Kata\Displays\ElevatorDisplayUpdatesWhenFloorChangeEventSubscriber
 */
class ElevatorDisplayUpdatesWhenFloorChangeEventSubscriberTest extends TestCase
{
    public function testDisplayIsProperlySaved(): void
    {
        $display = new ElevatorDisplay('el1');
        $subscriber = new ElevatorDisplayUpdatesWhenFloorChangeEventSubscriber($display, 'el1');
        $this->assertSame($display, $subscriber->getDisplay());
    }

    public function testElevatorIsProperlySaved(): void
    {
        $display = new ElevatorDisplay('el1');
        $subscriber = new ElevatorDisplayUpdatesWhenFloorChangeEventSubscriber($display, 'el1');
        $this->assertEquals('el1', $subscriber->getElevator());
    }

    public function testSubscriberListensToElevatorEvents(): void
    {
        $display = new ElevatorDisplay('el1');
        $subscriber = new ElevatorDisplayUpdatesWhenFloorChangeEventSubscriber($display, 'el1');
        $this->assertEquals('elevator-event', $subscriber->getEventName());
    }

    public function testDisplayGetsUpdatedWhenEventIsTriggered(): void
    {
        $display = new ElevatorDisplay('el1');
        $subscriber = new ElevatorDisplayUpdatesWhenFloorChangeEventSubscriber($display, 'el1');
        $subscriber->respond(new ElevatorFloorChangedEvent('changed-floor', 'el1', 3));
        $this->assertEquals(3, $display->getFloor());
    }

    public function testDisplayDoesNotGetUpdatedWhenEventIsTriggeredForADifferentElevator(): void
    {
        $display = new ElevatorDisplay('el1');
        $originalFloorOnDisplay = $display->getFloor();
        $subscriber = new ElevatorDisplayUpdatesWhenFloorChangeEventSubscriber($display, 'el2');
        $subscriber->respond(new ElevatorFloorChangedEvent('changed-floor', 'el1', 3));
        $this->assertEquals($originalFloorOnDisplay, $display->getFloor());
    }

    public function testAllDisplaysGetUpdatedWhenManyExistForTheSameElevatorOnDifferentFloors(): void
    {
        $floor0 = new Floor(0, Floor::BUTTON_NONE);
        $floor1 = new Floor(1, Floor::BUTTON_NONE);
        $floor2 = new Floor(2, Floor::BUTTON_NONE);
        $display0 = new ElevatorDisplay('el1');
        $display1 = new ElevatorDisplay('el1');
        $display2 = new ElevatorDisplay('el1');
        $floor0->setDisplay('el1', $display0);
        $floor1->setDisplay('el1', $display1);
        $floor2->setDisplay('el1', $display2);

        $elevatorDisplay = new ElevatorDisplay('el1');
        $elevator = new Elevator('el1');
        $elevator->setDisplay($elevatorDisplay);

        $this->assertEquals(0, $display0->getFloor());
        $this->assertEquals(0, $display1->getFloor());
        $this->assertEquals(0, $display2->getFloor());
        $this->assertEquals(0, $elevatorDisplay->getFloor());

        $elevator->move(2);
        $elevator->act();

        $this->assertEquals(1, $display0->getFloor());
        $this->assertEquals(1, $display1->getFloor());
        $this->assertEquals(1, $display2->getFloor());
        $this->assertEquals(1, $elevatorDisplay->getFloor());

        $elevator->act();

        $this->assertEquals(2, $display0->getFloor());
        $this->assertEquals(2, $display1->getFloor());
        $this->assertEquals(2, $display2->getFloor());
        $this->assertEquals(2, $elevatorDisplay->getFloor());
    }

    public function testOnlyDisplaysFromMovedElevatorGetsUpdatedWhenManyExistOnDifferentFloors(): void
    {
        $floor0 = new Floor(0, Floor::BUTTON_NONE);
        $floor1 = new Floor(1, Floor::BUTTON_NONE);
        $floor2 = new Floor(2, Floor::BUTTON_NONE);
        $display01 = new ElevatorDisplay('el1');
        $display11 = new ElevatorDisplay('el1');
        $display21 = new ElevatorDisplay('el1');
        $floor0->setDisplay('el1', $display01);
        $floor1->setDisplay('el1', $display11);
        $floor2->setDisplay('el1', $display21);

        $display02 = new ElevatorDisplay('el2');
        $display12 = new ElevatorDisplay('el2');
        $display22 = new ElevatorDisplay('el2');
        $floor0->setDisplay('el2', $display02);
        $floor1->setDisplay('el2', $display12);
        $floor2->setDisplay('el2', $display22);

        $elevatorDisplay1 = new ElevatorDisplay('el1');
        $elevator1 = new Elevator('el1');
        $elevator1->setDisplay($elevatorDisplay1);

        $elevatorDisplay2 = new ElevatorDisplay('el2');
        $elevator2 = new Elevator('el2');
        $elevator2->setDisplay($elevatorDisplay2);

        $this->assertEquals(0, $display01->getFloor());
        $this->assertEquals(0, $display11->getFloor());
        $this->assertEquals(0, $display21->getFloor());
        $this->assertEquals(0, $elevatorDisplay1->getFloor());

        $this->assertEquals(0, $display02->getFloor());
        $this->assertEquals(0, $display12->getFloor());
        $this->assertEquals(0, $display22->getFloor());
        $this->assertEquals(0, $elevatorDisplay2->getFloor());

        $elevator2->move(2);
        $elevator2->act();

        $this->assertEquals(0, $display01->getFloor());
        $this->assertEquals(0, $display11->getFloor());
        $this->assertEquals(0, $display21->getFloor());
        $this->assertEquals(0, $elevatorDisplay1->getFloor());

        $this->assertEquals(1, $display02->getFloor());
        $this->assertEquals(1, $display12->getFloor());
        $this->assertEquals(1, $display22->getFloor());
        $this->assertEquals(1, $elevatorDisplay2->getFloor());

        $elevator2->act();

        $this->assertEquals(0, $display01->getFloor());
        $this->assertEquals(0, $display11->getFloor());
        $this->assertEquals(0, $display21->getFloor());
        $this->assertEquals(0, $elevatorDisplay1->getFloor());

        $this->assertEquals(2, $display02->getFloor());
        $this->assertEquals(2, $display12->getFloor());
        $this->assertEquals(2, $display22->getFloor());
        $this->assertEquals(2, $elevatorDisplay2->getFloor());
    }
}
