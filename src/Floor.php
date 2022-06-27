<?php

namespace Kata;

class Floor
{
    public const BUTTON_NONE = 0;
    public const BUTTON_DOWN = 1;
    public const BUTTON_UP = 2;
    public const BUTTON_BOTH = self::BUTTON_DOWN + self::BUTTON_UP;

    private int $number;
    private int $buttons;

    /**
     * @var ElevatorDisplay[]
     */
    private array $displays = [];

    /**
     * @var LightIndicator[]
     */
    private array $lightIndicators = [];

    public function __construct(int $number, int $buttons)
    {
        $this->number = $number;
        $this->buttons = $buttons;
    }

    /**
     * @return int
     */
    public function getFloorNumber(): int
    {
        return $this->number;
    }

    public function hasDownButton(): bool
    {
        return ($this->buttons & self::BUTTON_DOWN) === self::BUTTON_DOWN;
    }

    public function hasUpButton(): bool
    {
        return ($this->buttons & self::BUTTON_UP) === self::BUTTON_UP;
    }

    public function callDownwards(): void
    {
        if (!$this->hasDownButton()) {
            throw new CannotPressDownButtonException($this);
        }
        EventPipeline::getInstance()->dispatchEvent(new FloorButtonEvent($this->getFloorNumber()));
    }

    public function callUpwards(): void
    {
        if (!$this->hasUpButton()) {
            throw new CannotPressUpButtonException($this);
        }
        EventPipeline::getInstance()->dispatchEvent(new FloorButtonEvent($this->getFloorNumber()));
    }

    public function setDisplay(string $elevator, ElevatorDisplay $display): void
    {
        $this->displays[$elevator] = $display;
    }

    public function getDisplay(string $elevator): ?ElevatorDisplay
    {
        if (!array_key_exists($elevator, $this->displays)) {
            return null;
        }
        return $this->displays[$elevator];
    }

    public function setLightIndicator(string $elevator, LightIndicator $lightIndicator): void
    {
        $this->lightIndicators[$elevator] = $lightIndicator;
    }

    public function getLightIndicator(string $elevator): ?LightIndicator
    {
        if (!array_key_exists($elevator, $this->lightIndicators)) {
            return null;
        }
        return $this->lightIndicators[$elevator];
    }
}
