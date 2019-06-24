<?php


namespace Antevenio\DddExample\Infrastructure;


use Antevenio\DddExample\Actions\ActionBus;
use Antevenio\DddExample\Domain\Event\EventNotification;
use Antevenio\DddExample\Domain\Event\EventStore;
use Antevenio\DddExample\Infrastructure\Bus\Tactician\TacticianActionBusFactory;
use Antevenio\DddExample\Infrastructure\EventNotification\EventNotificationFactory;
use Antevenio\DddExample\Infrastructure\EventStore\EventStoreFactory;
use Antevenio\DddExample\Infrastructure\Ui\Console\Command\EventNotificationCommand;
use Antevenio\DddExample\Infrastructure\Ui\Console\Command\EventNotificationCommandFactory;

class EventConfigProvider
{
    public static function getConfig($pdoServiceName, $actionRequestToActionMap)
    {
        return [
            // Event store
            EventNotification::class => new EventNotificationFactory(),
            EventStore::class => new EventStoreFactory($pdoServiceName),
            EventNotificationCommand::class => new EventNotificationCommandFactory(),
            // Action Bus
            ActionBus::class => new TacticianActionBusFactory(
                $pdoServiceName,
                $actionRequestToActionMap
            )
        ];
    }

}
