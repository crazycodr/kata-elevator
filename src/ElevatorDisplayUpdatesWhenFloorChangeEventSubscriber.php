<?php

namespace Kata;

class ElevatorDisplayUpdatesWhenFloorChangeEventSubscriber implements Subscriber
{
    private string $elevator;
    private ElevatorDisplay $display;

    public function __construct(ElevatorDisplay $display, string $elevator)
    {
        $this->display = $display;
        $this->elevator = $elevator;
    }

    public function getEventName(): string
    {
        return 'elevator-event';
    }

    public function respond(Event $event): void
    {
        /** @var ElevatorFloorChangedEvent $event */
        if ($event->getElevator() === $this->elevator) {
            $this->display->setFloor($event->getFloor());
        }
    }

    /**
     * @return ElevatorDisplay
     */
    public function getDisplay(): ElevatorDisplay
    {
        return $this->display;
    }

    /**
     * @return string
     */
    public function getElevator(): string
    {
        return $this->elevator;
    }
}
