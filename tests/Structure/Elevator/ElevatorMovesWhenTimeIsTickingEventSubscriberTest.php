<?php

namespace Structure\Elevator;

use Kata\Core\EventPipeline;
use Kata\Core\TickEvent;
use Kata\Structure\Elevator\Elevator;
use Kata\Structure\Elevator\ElevatorMovesWhenTimeIsTickingEventSubscriber;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Kata\Structure\Elevator\ElevatorMovesWhenTimeIsTickingEventSubscriber
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
