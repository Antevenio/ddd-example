<?php


namespace Antevenio\DddExample\Domain\Event;

interface EventNotification
{

    public function notify(DomainEvent $domainEvent): void;
}
