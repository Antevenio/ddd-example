<?php

namespace Antevenio\DddExample\Domain\Event;

class SpySubscriber implements DomainEventSubscriber
{
    public $domainEvent;
    public $isHandled = false;
    private $eventName;

    public function __construct($eventName)
    {
        $this->eventName = $eventName;
    }

    public function isSubscribedTo($aDomainEvent)
    {
        return $this->eventName === $aDomainEvent->getName();
    }

    public function handle(DomainEvent $domainEvent): void
    {
        $this->domainEvent = $domainEvent;
        $this->isHandled = true;
    }
}
