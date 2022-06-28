<?php

namespace Kata\Speakers;

use Kata\Core\Event;

class SpeakerEvent implements Event
{
    private string $soundType;

    public function __construct(string $soundType)
    {
        $this->soundType = $soundType;
    }

    public function getName(): string
    {
        return 'speaker-event';
    }

    /**
     * @return string
     */
    public function getSoundType(): string
    {
        return $this->soundType;
    }
}
