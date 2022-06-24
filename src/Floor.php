<?php

namespace Kata;

class Floor {

    const BUTTON_NONE = 0;
    const BUTTON_DOWN = 1;
    const BUTTON_UP = 2;
    const BUTTON_BOTH = self::BUTTON_DOWN + self::BUTTON_UP;

    private int $number;
    private int $buttons;
    private Elevator $elevator;

    public function __construct(Elevator $elevator, int $number, int $buttons) {
        $this->number = $number;
        $this->buttons = $buttons;
        $this->elevator = $elevator;
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
        $this->elevator->move($this->getFloorNumber());
    }

    public function callUpwards(): void
    {
        if (!$this->hasUpButton()) {
            throw new CannotPressUpButtonException($this);
        }
        $this->elevator->move($this->getFloorNumber());
    }

    /**
     * @return Elevator
     */
    public function getElevator(): Elevator
    {
        return $this->elevator;
    }


}