<?php

namespace Kata;

class Elevator {

    const DIRECTION_NONE = 'none';
    const DIRECTION_UP = 'up';
    const DIRECTION_DOWN = 'down';

    const STATE_WAITING = 'waiting';
    const STATE_MOVING = 'moving';

    private int $currentFloor = 0;
    private string $currentState = self::STATE_WAITING;
    private string $currentDirection = self::DIRECTION_NONE;

    private ?int $targetFloor = null;

    public function move(int $toFloor): void
    {
        if ($this->targetFloor !== null) {
            return;
        }
        $this->targetFloor = $toFloor;
    }

    public function act(): void
    {
        if (!$this->hasTarget()) {
            return;
        }

        $this->currentState = self::STATE_MOVING;
        $this->currentDirection = ($this->currentFloor < $this->targetFloor ? self::DIRECTION_UP : self::DIRECTION_DOWN);

        $this->moveElevator();

        if ($this->hasReachedDestination()) {
            $this->resetTargetAndState();
        }
    }

    /**
     * @return ?int
     */
    public function getTargetFloor(): ?int
    {
        return $this->targetFloor;
    }

    /**
     * @return string
     */
    public function getCurrentState(): string
    {
        return $this->currentState;
    }

    /**
     * @return string
     */
    public function getCurrentDirection(): string
    {
        return $this->currentDirection;
    }

    /**
     * @return int
     */
    public function getCurrentFloor(): int
    {
        return $this->currentFloor;
    }

    /**
     * @return void
     */
    public function moveElevator(): void
    {
        if ($this->currentDirection === self::DIRECTION_UP) {
            $this->currentFloor++;
        } elseif ($this->currentDirection === self::DIRECTION_DOWN) {
            $this->currentFloor--;
        } else {
            return;
        }

        EventPipeline::getInstance()->dispatchEvent(new ElevatorEvent('changed-floor'));
    }

    /**
     * @return bool
     */
    public function hasReachedDestination(): bool
    {
        return $this->currentFloor === $this->targetFloor;
    }

    /**
     * @return void
     */
    public function resetTargetAndState(): void
    {
        $this->targetFloor = null;
        $this->currentDirection = self::DIRECTION_NONE;
        $this->currentState = self::STATE_WAITING;
    }

    /**
     * @return bool
     */
    public function hasTarget(): bool
    {
        return $this->targetFloor !== null;
    }

}