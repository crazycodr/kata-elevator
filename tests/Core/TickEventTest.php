<?php

namespace Core;

use Kata\Core\TickEvent;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Kata\Core\TickEvent
 */
class TickEventTest extends TestCase
{
    public function testEventNameDoesNotChange(): void
    {
        $event = new TickEvent();
        $this->assertEquals('tick', $event->getName());
    }
}
