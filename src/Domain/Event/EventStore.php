<?php


namespace Antevenio\DddExample\Domain\Event;

interface EventStore
{

    /**
     * @param DomainEvent $domainEvent
     */
    public function append(DomainEvent $domainEvent) : void;

    /**
     * @return mixed
     */
    public function notifyStoredEvents() : void;
}
