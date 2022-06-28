<?php

namespace Kata\Core;

class TickEvent implements Event
{
    public function getName(): string
    {
        return 'tick';
    }
}
