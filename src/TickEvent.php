<?php

namespace Kata;

class TickEvent implements Event
{
    function getName(): string
    {
        return 'tick';
    }
}