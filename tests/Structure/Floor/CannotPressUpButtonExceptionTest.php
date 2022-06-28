<?php

namespace Structure\Floor;

use Kata\Structure\Floor\CannotPressUpButtonException;
use Kata\Structure\Floor\Floor;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Kata\Structure\Floor\CannotPressUpButtonException
 */
class CannotPressUpButtonExceptionTest extends TestCase
{
    public function testExceptionExposesFloor(): void
    {
        $floor = new Floor(11, Floor::BUTTON_NONE);
        $exception = new CannotPressUpButtonException($floor);
        $this->assertSame($floor, $exception->getFloor());
    }
}
