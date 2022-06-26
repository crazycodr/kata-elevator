<?php

namespace Kata;

class TickEvent implements Event
{
    public function getName(): string
    {
        return 'tick';
    }
}
