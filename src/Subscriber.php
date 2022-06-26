<?php

namespace Kata;

interface Subscriber
{
    public function getEventName(): string;
    public function respond(Event $event): void;
}
