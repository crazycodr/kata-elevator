<?php

namespace Kata;

class Elevator
{

    const DIRECTION_NONE = 'none';
    const DIRECTION_UP = 'up';
    const DIRECTION_DOWN = 'down';

    const STATE_WAITING = 'waiting';
    const STATE_MOVING = 'moving';
    const STATE_OPENING = 'opening';
    const STATE_OPEN = 'open';
    const STATE_CLOSING = 'closing';
    const STATE_CLOSED = 'closed';

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
        if ($this->hasReachedDestination() && $this->isInDoorState()) {
            $this->moveDoors();
        }

        if ($this->hasReachedDestination() && $this->isMoving()) {
            $this->stopElevator();
            $this->resetTarget();
            $this->moveDoors();
        }

        if (!$this->hasReachedDestination() && $this->hasTarget()) {
            $this->moveElevator();
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
        $this->currentState = self::STATE_MOVING;
        $this->currentDirection = ($this->currentFloor < $this->targetFloor ? self::DIRECTION_UP : self::DIRECTION_DOWN);

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
     * @return void
     */
    public function stopElevator(): void
    {
        $this->currentState = self::STATE_WAITING;
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
    public function resetTarget(): void
    {
        $this->targetFloor = null;
    }

    /**
     * @return bool
     */
    public function hasTarget(): bool
    {
        return $this->targetFloor !== null;
    }

    /**
     * @return bool
     */
    public function isMoving(): bool
    {
        return $this->getCurrentState() === self::STATE_MOVING;
    }

    /**
     * @return bool
     */
    public function isInDoorState(): bool
    {
        return $this->getCurrentState() === self::STATE_OPEN
            || $this->getCurrentState() === self::STATE_OPENING
            || $this->getCurrentState() === self::STATE_CLOSING
            || $this->getCurrentState() === self::STATE_CLOSED;
    }

    private function moveDoors(): void
    {
        if ($this->getCurrentState() === self::STATE_CLOSING) {
            $this->currentState = self::STATE_CLOSED;
        } elseif ($this->getCurrentState() === self::STATE_OPEN) {
            $this->currentState = self::STATE_CLOSING;
        } elseif ($this->getCurrentState() === self::STATE_OPENING) {
            $this->currentState = self::STATE_OPEN;
        } elseif ($this->getCurrentState() === self::STATE_WAITING) {
            $this->currentState = self::STATE_CLOSED;
        }
    }

}