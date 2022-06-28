<?php

namespace Speakers;

use Kata\Speakers\SpeakerEvent;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Kata\Speakers\SpeakerEvent
 */
class SpeakerEventTest extends TestCase
{
    public function testSpeakerEventNameNeverChanges(): void
    {
        $this->assertEquals('speaker-event', (new SpeakerEvent('dummy'))->getName());
    }

    public function testSpeakerEventRemembersSoundType(): void
    {
        $this->assertEquals('sound-type', (new SpeakerEvent('sound-type'))->getSoundType());
    }
}
