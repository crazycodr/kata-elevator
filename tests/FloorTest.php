<?php

namespace Kata;

use JetBrains\PhpStorm\ArrayShape;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Kata\Floor
 */
class FloorTest extends TestCase
{
    public function testNewFloorSavesTheElevatorProperly(): void
    {
        $elevator = new Elevator();
        $floor = new Floor($elevator, 0, Floor::BUTTON_NONE);
        $this->assertSame($elevator, $floor->getElevator());
    }

    public function testNewFloorSavesTheFloorProperly(): void
    {
        $elevator = new Elevator();
        $floor = new Floor($elevator, 6, Floor::BUTTON_NONE);
        $this->assertEquals(6, $floor->getFloorNumber());
    }

    /**
     * @dataProvider providesNewFloorSavesTheButtonsProperly
     */
    public function testNewFloorSavesTheButtonsProperly(int $buttons, bool $upButtonShouldExist, bool $downButtonShouldExist): void
    {
        $elevator = new Elevator();
        $floor = new Floor($elevator, 0, $buttons);
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
        $floor = new Floor($elevator, 0, Floor::BUTTON_NONE);
        $this->expectException(CannotPressUpButtonException::class);
        $floor->callUpwards();
    }

    public function testCallingElevatorDownWhenThereIsNoButtonShouldThrowException(): void
    {
        $elevator = new Elevator();
        $floor = new Floor($elevator, 0, Floor::BUTTON_NONE);
        $this->expectException(CannotPressDownButtonException::class);
        $floor->callDownwards();
    }

    public function testCallingElevatorShouldIndeedChangeTheTargetFloorOnTheElevator(): void
    {
        $elevator = new Elevator();
        $floor = new Floor($elevator, 1, Floor::BUTTON_BOTH);
        $this->assertNotEquals(1, $elevator->getTargetFloor());
        $floor->callUpwards();
        $this->assertEquals(1, $elevator->getTargetFloor());
    }
}
