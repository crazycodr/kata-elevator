<?php

namespace Kata\Structure\Floor;

use RuntimeException;

class CannotPressUpButtonException extends RuntimeException
{
    private Floor $floor;

    public function __construct(Floor $floor)
    {
        parent::__construct('Cannot go up from here');
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
