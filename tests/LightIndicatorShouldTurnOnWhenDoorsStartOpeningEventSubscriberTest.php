<?php

namespace Kata;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Kata\LightIndicatorShouldTurnOnWhenDoorsStartOpeningEventSubscriber
 */
class LightIndicatorShouldTurnOnWhenDoorsStartOpeningEventSubscriberTest extends TestCase
{
    public function testItSavesElevatorProperly(): void
    {
        $light = new LightIndicator('el1', 11);
        $subscriber = new LightIndicatorShouldTurnOnWhenDoorsStartOpeningEventSubscriber($light, 'el1', 11);
        $this->assertEquals('el1', $subscriber->getElevator());
    }

    public function testItSavesFloorProperly(): void
    {
        $light = new LightIndicator('el1', 11);
        $subscriber = new LightIndicatorShouldTurnOnWhenDoorsStartOpeningEventSubscriber($light, 'el1', 11);
        $this->assertEquals(11, $subscriber->getFloor());
    }

    public function testItSavesLightProperly(): void
    {
        $light = new LightIndicator('el1', 11);
        $subscriber = new LightIndicatorShouldTurnOnWhenDoorsStartOpeningEventSubscriber($light, 'el1', 11);
        $this->assertSame($light, $subscriber->getLightIndicator());
    }

    public function testDoesNotTurnOnLightIfNotTheSameElevator(): void
    {
        $light = new LightIndicator('el1', 11);
        $subscriber = new LightIndicatorShouldTurnOnWhenDoorsStartOpeningEventSubscriber($light, 'el1', 11);
        $subscriber->respond(new DoorEvent('opening', 'el2', 11));
        $this->assertFalse($light->isLit());
    }

    public function testDoesNotTurnOnLightIfNotTheSameFloor(): void
    {
        $light = new LightIndicator('el1', 11);
        $subscriber = new LightIndicatorShouldTurnOnWhenDoorsStartOpeningEventSubscriber($light, 'el1', 11);
        $subscriber->respond(new DoorEvent('opening', 'el1', 10));
        $this->assertFalse($light->isLit());
    }

    public function testDoesNotTurnOnLightIfDoorsAreNotOpening(): void
    {
        $light = new LightIndicator('el1', 11);
        $subscriber = new LightIndicatorShouldTurnOnWhenDoorsStartOpeningEventSubscriber($light, 'el1', 11);
        $subscriber->respond(new DoorEvent('closing', 'el1', 11));
        $this->assertFalse($light->isLit());
    }

    public function testTurnsOnLightIfSameFloorAndElevatorAndDoorsOpening(): void
    {
        $light = new LightIndicator('el1', 11);
        $subscriber = new LightIndicatorShouldTurnOnWhenDoorsStartOpeningEventSubscriber($light, 'el1', 11);
        $subscriber->respond(new DoorEvent('opening', 'el1', 11));
        $this->assertTrue($light->isLit());
    }
}
