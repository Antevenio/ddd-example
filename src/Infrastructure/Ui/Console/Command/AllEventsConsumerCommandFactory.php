<?php


namespace Antevenio\DddExample\Infrastructure\Ui\Console\Command;

use Antevenio\DddExample\Domain\Metric\MetricService;
use Antevenio\DddExample\Infrastructure\EventNotification\AmqpEventSubscriber;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

class AllEventsConsumerCommandFactory
{
    const QUEUE_NAME = 'AllEvents';
    const BINDING_KEYS = [
        '*'
    ];

    public function __invoke(ContainerInterface $container)
    {
        $logger = $container->get(LoggerInterface::class);
        $rabbitConfig = $container->get('config')['rabbit'];
        $amqpEventSubscriber = new AmqpEventSubscriber(
            $logger,
            $rabbitConfig,
            self::QUEUE_NAME,
            self::BINDING_KEYS
        );
        $metricService = $container->get(MetricService::class);
        return new AllEventsConsumerCommand($logger, $amqpEventSubscriber, $metricService);
    }
}
