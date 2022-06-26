<?php

namespace Kata;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Kata\TickEvent
 */
class TickEventTest extends TestCase
{
    public function testEventNameDoesNotChange(): void
    {
        $event = new TickEvent();
        $this->assertEquals('tick', $event->getName());
    }
}
