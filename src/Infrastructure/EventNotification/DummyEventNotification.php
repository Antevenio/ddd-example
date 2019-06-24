<?php


namespace Antevenio\DddExample\Infrastructure\EventNotification;

use Antevenio\DddExample\Domain\Event\DomainEvent;
use Antevenio\DddExample\Domain\Event\EventNotification;

class DummyEventNotification implements EventNotification
{

    public function notify(DomainEvent $domainEvent): void
    {
        echo "\nNotifying " . json_encode($domainEvent);
    }
}
