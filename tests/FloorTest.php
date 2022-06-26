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
        $elevator = new Elevator();
        $floor = new Floor(6, Floor::BUTTON_NONE);
        $this->assertEquals(6, $floor->getFloorNumber());
    }

    /**
     * @dataProvider providesNewFloorSavesTheButtonsProperly
     */
    public function testNewFloorSavesTheButtonsProperly(int $buttons, bool $upButtonShouldExist, bool $downButtonShouldExist): void
    {
        $elevator = new Elevator();
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
        $elevator = new Elevator();
        $floor = new Floor(0, Floor::BUTTON_NONE);
        $this->expectException(CannotPressUpButtonException::class);
        $floor->callUpwards();
    }

    public function testCallingElevatorDownWhenThereIsNoButtonShouldThrowException(): void
    {
        $elevator = new Elevator();
        $floor = new Floor(0, Floor::BUTTON_NONE);
        $this->expectException(CannotPressDownButtonException::class);
        $floor->callDownwards();
    }

    public function testCallingElevatorShouldIndeedChangeTheTargetFloorOnTheElevator(): void
    {
        $elevator = new Elevator();
        EventPipeline::getInstance()->addSubscriber(new ElevatorSetsTargetWhenNotMovingAndFloorButtonEventOccursEventSubscriber($elevator));
        $floor = new Floor(4, Floor::BUTTON_BOTH);
        $this->assertNotEquals(1, $elevator->getTargetFloor());
        $floor->callUpwards();
        $this->assertEquals(4, $elevator->getTargetFloor());
    }

    public function testCallingElevatorShouldNotChangeTheTargetFloorOnTheElevatorBecauseThereIsAlreadyATarget(): void
    {
        $elevator = new Elevator();
        EventPipeline::getInstance()->addSubscriber(new ElevatorSetsTargetWhenNotMovingAndFloorButtonEventOccursEventSubscriber($elevator));
        $floor4 = new Floor(4, Floor::BUTTON_BOTH);
        $floor7 = new Floor(7, Floor::BUTTON_BOTH);
        $this->assertNotEquals(1, $elevator->getTargetFloor());
        $floor4->callUpwards();
        $floor7->callUpwards();
        $this->assertEquals(4, $elevator->getTargetFloor());
    }
}
