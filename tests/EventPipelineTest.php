<?php

namespace Kata;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Kata\EventPipeline
 */
class EventPipelineTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        EventPipeline::setInstance(null);
    }

    public function testGetInstanceWillCreateAnInstanceIfNoneSet(): void
    {
        $this->assertInstanceOf(EventPipeline::class, EventPipeline::getInstance());
    }

    public function testGetInstanceWillReturnTheSameInstancePreviouslySet(): void
    {
        $eventPipeline = EventPipeline::getInstance();
        $this->assertSame($eventPipeline, EventPipeline::getInstance());
    }

    public function testSetInstanceWillResetTheInternalInstance(): void
    {
        $eventPipeline = EventPipeline::getInstance();
        EventPipeline::setInstance(null);
        $newEventPipeline = EventPipeline::getInstance();
        $this->assertNotSame($eventPipeline, $newEventPipeline);
    }

    public function testMultipleSubscribersCanBeAddedTheEventPipeline(): void
    {
        $eventPipeline = EventPipeline::getInstance();
        $this->assertCount(0, $eventPipeline->getSubscribers());
        $eventPipeline->addSubscriber($this->getSubscriberMock());
        $eventPipeline->addSubscriber($this->getSubscriberMock());
        $eventPipeline->addSubscriber($this->getSubscriberMock());
        $this->assertCount(3, $eventPipeline->getSubscribers());
    }

    public function testDispatchingEventsWillCallTheRespondMethodOnSubscribers(): void
    {
        $eventPipeline = EventPipeline::getInstance();
        $eventPipeline->addSubscriber(
            $this->getSubscriberMockExpectingAResponse(
                $event = $this->getEventForSubscriberMockExpectingAResponse()
            )
        );
        $eventPipeline->addSubscriber($this->getSubscriberMock());
        $eventPipeline->dispatchEvent($event);
    }

    public function getSubscriberMock(): Subscriber|MockObject
    {
        return $this->createMock(Subscriber::class);
    }

    public function getSubscriberMockExpectingAResponse(Event $event): Subscriber|MockObject
    {
        $subscriber = $this->getSubscriberMock();
        $subscriber->method('getEventName')->willReturn($event->getName());
        $subscriber->expects($this->once())->method('respond')->with($event);
        return $subscriber;
    }

    public function getEventForSubscriberMockExpectingAResponse(): Event|MockObject {
        $event = $this->createMock(Event::class);
        $event->method('getName')->willReturn('some-event');
        return $event;
    }
}
