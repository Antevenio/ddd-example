<?php

namespace Antevenio\DddExample\Domain\Event;

class CollectInMemoryDomainEventsSubscriber implements DomainEventSubscriber
{
    private $events;

    public function __construct()
    {
        $this->events = [];
    }

    public function handle(DomainEvent $domainEvent) : void
    {
        if (!$this->isSubscribedTo($domainEvent)) {
            return;
        }

        $this->events[] = $domainEvent;
    }

    public function isSubscribedTo(DomainEvent $domainEvent) : bool
    {
        return $domainEvent instanceof PublishableDomainEvent;
    }

    public function events() : array
    {
        return $this->events;
    }
}
