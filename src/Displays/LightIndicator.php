<?php

namespace Kata\Displays;

use Kata\Core\EventPipeline;

class LightIndicator
{
    private string $elevator;
    private int $floor;
    private bool $lit = false;

    public function __construct(string $elevator, int $floor)
    {
        $this->elevator = $elevator;
        $this->floor = $floor;
        EventPipeline::getInstance()->addSubscriber(new LightIndicatorShouldTurnOnWhenDoorsStartOpeningEventSubscriber($this, $elevator, $floor));
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

    public function turnOn(): void
    {
        $this->lit = true;
    }

    public function turnOff(): void
    {
        $this->lit = false;
    }

    /**
     * @return bool
     */
    public function isLit(): bool
    {
        return $this->lit;
    }
}
