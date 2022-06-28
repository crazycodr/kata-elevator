<?php

namespace Kata\Structure\Elevator;

use Kata\Core\EventPipeline;
use Kata\Displays\ElevatorDisplay;
use Kata\Structure\DoorEvent;

class Elevator
{
    public const DIRECTION_NONE = 'none';
    public const DIRECTION_UP = 'up';
    public const DIRECTION_DOWN = 'down';

    public const STATE_WAITING = 'waiting';
    public const STATE_MOVING = 'moving';
    public const STATE_OPENING = 'opening';
    public const STATE_OPEN = 'open';
    public const STATE_CLOSING = 'closing';
    public const STATE_CLOSED = 'closed';

    private int $currentFloor = 0;
    private string $currentState = self::STATE_WAITING;
    private string $currentDirection = self::DIRECTION_NONE;

    private ?int $targetFloor = null;
    private string $id;
    private ?ElevatorDisplay $display = null;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function setDisplay(ElevatorDisplay $display): void
    {
        $this->display = $display;
    }

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

        if ($this->isInDoorState()) {
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

        EventPipeline::getInstance()->dispatchEvent(new ElevatorFloorChangedEvent('changed-floor', $this->getId(), $this->getCurrentFloor()));
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
        if ($this->getCurrentState() === self::STATE_WAITING) {
            $this->currentState = self::STATE_OPENING;
            EventPipeline::getInstance()->dispatchEvent(new DoorEvent('opening', $this->getId(), $this->getCurrentFloor()));
        } elseif ($this->getCurrentState() === self::STATE_OPENING) {
            $this->currentState = self::STATE_OPEN;
            EventPipeline::getInstance()->dispatchEvent(new DoorEvent('opened', $this->getId(), $this->getCurrentFloor()));
        } elseif ($this->getCurrentState() === self::STATE_OPEN) {
            $this->currentState = self::STATE_CLOSING;
            EventPipeline::getInstance()->dispatchEvent(new DoorEvent('closing', $this->getId(), $this->getCurrentFloor()));
        } elseif ($this->getCurrentState() === self::STATE_CLOSING) {
            $this->currentState = self::STATE_CLOSED;
            EventPipeline::getInstance()->dispatchEvent(new DoorEvent('closed', $this->getId(), $this->getCurrentFloor()));
        }
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return ?ElevatorDisplay
     */
    public function getDisplay(): ?ElevatorDisplay
    {
        return $this->display;
    }
}
