<?php

namespace Displays;

use Kata\Displays\LightIndicator;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Kata\Displays\LightIndicator
 */
class LightIndicatorTest extends TestCase
{
    public function testStartsTurnedOff(): void
    {
        $light = new LightIndicator('el1', 11);
        $this->assertFalse($light->isLit());
    }

    public function testElevatorIsProperlyPersisted(): void
    {
        $light = new LightIndicator('el1', 11);
        $this->assertEquals('el1', $light->getElevator());
    }

    public function testFloorIsProperlyPersisted(): void
    {
        $light = new LightIndicator('el1', 11);
        $this->assertEquals(11, $light->getFloor());
    }
}
