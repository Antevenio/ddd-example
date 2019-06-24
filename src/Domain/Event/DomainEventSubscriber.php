<?php

namespace Antevenio\DddExample\Domain\Event;

interface DomainEventSubscriber
{
    public function handle(DomainEvent $domainEvent) : void;
}
