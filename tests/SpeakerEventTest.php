<?php

use Kata\SpeakerEvent;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Kata\SpeakerEvent
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
