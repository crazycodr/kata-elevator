<?php

use Kata\DoorEvent;
use Kata\LightIndicator;
use Kata\LightIndicatorShouldTurnOffWhenDoorsStartClosingEventSubscriber;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Kata\LightIndicatorShouldTurnOffWhenDoorsStartClosingEventSubscriber
 */
class LightIndicatorShouldTurnOffWhenDoorsStartClosingEventSubscriberTest extends TestCase
{
    public function testItSavesElevatorProperly(): void
    {
        $light = new LightIndicator('el1', 11);
        $subscriber = new LightIndicatorShouldTurnOffWhenDoorsStartClosingEventSubscriber($light, 'el1', 11);
        $this->assertEquals('el1', $subscriber->getElevator());
    }

    public function testItSavesFloorProperly(): void
    {
        $light = new LightIndicator('el1', 11);
        $subscriber = new LightIndicatorShouldTurnOffWhenDoorsStartClosingEventSubscriber($light, 'el1', 11);
        $this->assertEquals(11, $subscriber->getFloor());
    }

    public function testItSavesLightProperly(): void
    {
        $light = new LightIndicator('el1', 11);
        $subscriber = new LightIndicatorShouldTurnOffWhenDoorsStartClosingEventSubscriber($light, 'el1', 11);
        $this->assertSame($light, $subscriber->getLightIndicator());
    }

    public function testDoesNotTurnOffLightIfNotTheSameElevator(): void
    {
        $light = new LightIndicator('el1', 11);
        $light->turnOn();
        $subscriber = new LightIndicatorShouldTurnOffWhenDoorsStartClosingEventSubscriber($light, 'el1', 11);
        $subscriber->respond(new DoorEvent('closing', 'el2', 11));
        $this->assertTrue($light->isLit());
    }

    public function testDoesNotTurnOffLightIfNotTheSameFloor(): void
    {
        $light = new LightIndicator('el1', 11);
        $light->turnOn();
        $subscriber = new LightIndicatorShouldTurnOffWhenDoorsStartClosingEventSubscriber($light, 'el1', 11);
        $subscriber->respond(new DoorEvent('closing', 'el1', 10));
        $this->assertTrue($light->isLit());
    }

    public function testDoesNotTurnOffLightIfDoorsAreNotClosing(): void
    {
        $light = new LightIndicator('el1', 11);
        $light->turnOn();
        $subscriber = new LightIndicatorShouldTurnOffWhenDoorsStartClosingEventSubscriber($light, 'el1', 11);
        $subscriber->respond(new DoorEvent('open', 'el1', 11));
        $this->assertTrue($light->isLit());
    }

    public function testTurnsOffLightIfSameFloorAndElevatorAndDoorsClosing(): void
    {
        $light = new LightIndicator('el1', 11);
        $light->turnOn();
        $subscriber = new LightIndicatorShouldTurnOffWhenDoorsStartClosingEventSubscriber($light, 'el1', 11);
        $subscriber->respond(new DoorEvent('closing', 'el1', 11));
        $this->assertFalse($light->isLit());
    }
}
