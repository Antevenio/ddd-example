<?php

namespace Antevenio\DddExample\Domain\Event;

final class DomainEventPublisher
{
    private static $instance;

    private $subscribers;

    private function __construct()
    {
        $this->subscribers = [];
    }

    public static function instance() : self
    {
        if (null === static::$instance) {
            static::$instance = new self();
        }

        return static::$instance;
    }

    public function __clone()
    {
        throw new DomainEventPublisherCloningIsNotAllowedException();
    }


    public function subscriberOfClassName(string $className) : DomainEventSubscriber
    {
        return $this->subscribers[$className];
    }

    public function subscribe(DomainEventSubscriber $domainEventSubscriber) : void
    {
        $this->subscribers[get_class($domainEventSubscriber)] = $domainEventSubscriber;
    }

    public function unsubscribe(DomainEventSubscriber $domainEventSubscriber) : void
    {
        unset($this->subscribers[get_class($domainEventSubscriber)]);
    }

    public function publish(DomainEvent $domainEvent) : void
    {
        foreach ($this->subscribers as $subscriber) {
            if ($subscriber->isSubscribedTo($domainEvent)) {
                $subscriber->handle($domainEvent);
            }
        }
    }

    public function unsubscribeAll() : void
    {
        $this->subscribers = [];
    }
}
