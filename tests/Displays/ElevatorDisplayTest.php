<?php

namespace Displays;

use Kata\Displays\ElevatorDisplay;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Kata\Displays\ElevatorDisplay
 */
class ElevatorDisplayTest extends TestCase
{
    public function testElevatorIsSaved()
    {
        $display = new ElevatorDisplay('el1');
        $this->assertEquals('el1', $display->getElevator());
    }

    public function testDefaultFloorIs0()
    {
        $display = new ElevatorDisplay('el1');
        $this->assertEquals(0, $display->getFloor());
    }

    public function testSettingFloorSavesNewFloor()
    {
        $display = new ElevatorDisplay('el1');
        $display->setFloor(11);
        $this->assertEquals(11, $display->getFloor());
    }
}
