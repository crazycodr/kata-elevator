<?php

namespace Kata;

class LightIndicatorShouldTurnOnWhenDoorsStartOpeningEventSubscriber implements Subscriber
{
    private LightIndicator $lightIndicator;
    private string $elevator;
    private int $floor;

    public function __construct(LightIndicator $lightIndicator, string $elevator, int $floor)
    {
        $this->lightIndicator = $lightIndicator;
        $this->elevator = $elevator;
        $this->floor = $floor;
    }

    /**
     * @return LightIndicator
     */
    public function getLightIndicator(): LightIndicator
    {
        return $this->lightIndicator;
    }

    /**
     * @return string
     */
    public function getElevator(): string
    {
        return $this->elevator;
    }

    /**
     * @return int
     */
    public function getFloor(): int
    {
        return $this->floor;
    }

    public function getEventName(): string
    {
        return 'door-event';
    }

    public function respond(Event $event): void
    {
        /** @var DoorEvent $event */
        if ($event->getElevator() !== $this->elevator || $event->getFloor() !== $this->floor) {
            return;
        }
        if ($event->getEventType() !== 'opening') {
            return;
        }
        $this->lightIndicator->turnOn();
    }
}
