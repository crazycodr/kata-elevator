<?php

namespace Kata;

use JetBrains\PhpStorm\ArrayShape;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Kata\Floor
 */
class FloorTest extends TestCase
{
    public function testNewFloorSavesTheFloorProperly(): void
    {
        $elevator = new Elevator('el1');
        $floor = new Floor(6, Floor::BUTTON_NONE);
        $this->assertEquals(6, $floor->getFloorNumber());
    }

    /**
     * @dataProvider providesNewFloorSavesTheButtonsProperly
     */
    public function testNewFloorSavesTheButtonsProperly(int $buttons, bool $upButtonShouldExist, bool $downButtonShouldExist): void
    {
        $elevator = new Elevator('el1');
        $floor = new Floor(0, $buttons);
        $this->assertEquals($upButtonShouldExist, $floor->hasUpButton());
        $this->assertEquals($downButtonShouldExist, $floor->hasDownButton());
    }

    #[ArrayShape(['no-buttons' => "array", 'up-button' => "array", 'down-button' => "array", 'both-buttons' => "array"])]
    public function providesNewFloorSavesTheButtonsProperly(): array
    {
        return [
            'no-buttons' => [Floor::BUTTON_NONE, false, false],
            'up-button' => [Floor::BUTTON_UP, true, false],
            'down-button' => [Floor::BUTTON_DOWN, false, true],
            'both-buttons' => [Floor::BUTTON_BOTH, true, true],
        ];
    }

    public function testCallingElevatorUpWhenThereIsNoButtonShouldThrowException(): void
    {
        $elevator = new Elevator('el1');
        $floor = new Floor(0, Floor::BUTTON_NONE);
        $this->expectException(CannotPressUpButtonException::class);
        $floor->callUpwards();
    }

    public function testCallingElevatorDownWhenThereIsNoButtonShouldThrowException(): void
    {
        $elevator = new Elevator('el1');
        $floor = new Floor(0, Floor::BUTTON_NONE);
        $this->expectException(CannotPressDownButtonException::class);
        $floor->callDownwards();
    }

    public function testCallingElevatorShouldIndeedChangeTheTargetFloorOnTheElevator(): void
    {
        $elevator = new Elevator('el1');
        EventPipeline::getInstance()->addSubscriber(new ElevatorSetsTargetWhenNotMovingAndFloorButtonEventOccursEventSubscriber($elevator));
        $floor = new Floor(4, Floor::BUTTON_BOTH);
        $this->assertNotEquals(1, $elevator->getTargetFloor());
        $floor->callUpwards();
        $this->assertEquals(4, $elevator->getTargetFloor());
    }

    public function testCallingElevatorShouldNotChangeTheTargetFloorOnTheElevatorBecauseThereIsAlreadyATarget(): void
    {
        $elevator = new Elevator('el1');
        EventPipeline::getInstance()->addSubscriber(new ElevatorSetsTargetWhenNotMovingAndFloorButtonEventOccursEventSubscriber($elevator));
        $floor4 = new Floor(4, Floor::BUTTON_BOTH);
        $floor7 = new Floor(7, Floor::BUTTON_BOTH);
        $this->assertNotEquals(1, $elevator->getTargetFloor());
        $floor4->callUpwards();
        $floor7->callUpwards();
        $this->assertEquals(4, $elevator->getTargetFloor());
    }

    public function testFloorDoesNotHaveADisplayByDefaultAndReturnsNullWhenElevatorIdHasNoBoundDisplay(): void
    {
        $floor = new Floor(1, Floor::BUTTON_NONE);
        $this->assertNull($floor->getDisplay('el1'));
    }

    public function testFloorSavesDisplayProperlyForEachElevatorId(): void
    {
        $display1 = new ElevatorDisplay('el1');
        $display2 = new ElevatorDisplay('el2');
        $display3 = new ElevatorDisplay('el3');
        $floor = new Floor(1, Floor::BUTTON_NONE);
        $floor->setDisplay('el1', $display1);
        $floor->setDisplay('el2', $display2);
        $floor->setDisplay('el3', $display3);
        $this->assertSame($display1, $floor->getDisplay('el1'));
        $this->assertSame($display2, $floor->getDisplay('el2'));
        $this->assertSame($display3, $floor->getDisplay('el3'));
    }

    public function testFloorReplacesDisplayForExistingElevatorId(): void
    {
        $display1 = new ElevatorDisplay('el1');
        $display2 = new ElevatorDisplay('el1');
        $floor = new Floor(1, Floor::BUTTON_NONE);
        $floor->setDisplay('el1', $display1);
        $this->assertSame($display1, $floor->getDisplay('el1'));
        $floor->setDisplay('el1', $display2);
        $this->assertSame($display2, $floor->getDisplay('el1'));
    }

    public function testFloorDoesNotHaveALightIndicatorByDefaultAndReturnsNullWhenElevatorIdHasNoBoundLightIndicator(): void
    {
        $floor = new Floor(1, Floor::BUTTON_NONE);
        $this->assertNull($floor->getLightIndicator('el1'));
    }

    public function testFloorSavesLightIndicatorProperlyForEachElevatorId(): void
    {
        $display1 = new LightIndicator('el1', 1);
        $display2 = new LightIndicator('el2', 1);
        $display3 = new LightIndicator('el3', 1);
        $floor = new Floor(1, Floor::BUTTON_NONE);
        $floor->setLightIndicator('el1', $display1);
        $floor->setLightIndicator('el2', $display2);
        $floor->setLightIndicator('el3', $display3);
        $this->assertSame($display1, $floor->getLightIndicator('el1'));
        $this->assertSame($display2, $floor->getLightIndicator('el2'));
        $this->assertSame($display3, $floor->getLightIndicator('el3'));
    }

    public function testFloorReplacesLightIndicatorForExistingElevatorId(): void
    {
        $display1 = new LightIndicator('el1', 1);
        $display2 = new LightIndicator('el1', 1);
        $floor = new Floor(1, Floor::BUTTON_NONE);
        $floor->setLightIndicator('el1', $display1);
        $this->assertSame($display1, $floor->getLightIndicator('el1'));
        $floor->setLightIndicator('el1', $display2);
        $this->assertSame($display2, $floor->getLightIndicator('el1'));
    }
}
