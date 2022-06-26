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
}
