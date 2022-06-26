<?php

namespace Kata;

use RuntimeException;

class CannotPressDownButtonException extends RuntimeException
{
    private Floor $floor;

    public function __construct(Floor $floor)
    {
        parent::__construct('Cannot go down from here');
        $this->floor = $floor;
    }

    /**
     * @return Floor
     */
    public function getFloor(): Floor
    {
        return $this->floor;
    }
}
