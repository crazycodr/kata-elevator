<?php

namespace Kata;

interface Subscriber {
    function getEventName(): string;
    function respond(Event $event): void;
}