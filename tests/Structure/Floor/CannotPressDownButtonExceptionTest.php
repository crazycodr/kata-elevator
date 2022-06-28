<?php

namespace Structure\Floor;

use Kata\Structure\Floor\CannotPressDownButtonException;
use Kata\Structure\Floor\Floor;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Kata\Structure\Floor\CannotPressDownButtonException
 */
class CannotPressDownButtonExceptionTest extends TestCase
{
    public function testExceptionExposesFloor(): void
    {
        $floor = new Floor(11, Floor::BUTTON_NONE);
        $exception = new CannotPressDownButtonException($floor);
        $this->assertSame($floor, $exception->getFloor());
    }
}
