<?php

namespace Kata;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Kata\ElevatorMovesWhenTimeIsTickingEventSubscriber
 */
class ElevatorMovesWhenTimeIsTickingEventSubscriberTest extends TestCase
{
    public function testElevatorMoveIsCalledWhenRespondingToEvent(): void
    {
        $elevator = $this->createMock(Elevator::class);
        $elevator->expects($this->once())->method('act');
        $subscriber = new ElevatorMovesWhenTimeIsTickingEventSubscriber($elevator);
        $subscriber->respond(new TickEvent());
    }

    public function testSubscriberIsProperlyRespondingToTickEventsThroughPipeline(): void
    {
        $elevator = $this->createMock(Elevator::class);
        $elevator->expects($this->once())->method('act');
        $subscriber = new ElevatorMovesWhenTimeIsTickingEventSubscriber($elevator);
        $pipeline = EventPipeline::getInstance();
        $pipeline->addSubscriber($subscriber);
        $pipeline->dispatchEvent(new TickEvent());
    }
}
