<?php

namespace Kata;

class EventPipeline
{
    private static ?EventPipeline $instance = null;

    /**
     * @var Subscriber[]
     */
    private array $subscribers = [];

    public static function getInstance(): EventPipeline
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function setInstance(?EventPipeline $instance): void
    {
        self::$instance = $instance;
    }

    public function addSubscriber(Subscriber $subscriber): void
    {
        $this->subscribers[] = $subscriber;
    }

    /**
     * @return Subscriber[]
     */
    public function getSubscribers(): array
    {
        return $this->subscribers;
    }

    public function dispatchEvent(Event $event): void
    {
        foreach ($this->subscribers as $subscriber) {
            if ($subscriber->getEventName() === $event->getName()) {
                $subscriber->respond($event);
            }
        }
    }
}
