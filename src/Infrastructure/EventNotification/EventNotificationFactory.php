<?php


namespace Antevenio\DddExample\Infrastructure\EventNotification;

use Interop\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

class EventNotificationFactory
{

    public function __invoke(ContainerInterface $container)
    {
        $logger = $container->get(LoggerInterface::class);
        $configRabbit = $container->get('config')['rabbit'];
        return new AmqpEventNotification($logger, $configRabbit);
    }
}
