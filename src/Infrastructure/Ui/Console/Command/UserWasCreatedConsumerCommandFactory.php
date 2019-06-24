<?php


namespace Antevenio\DddExample\Infrastructure\Ui\Console\Command;

use Antevenio\DddExample\Domain\Model\User\UserWasCreated;
use Antevenio\DddExample\Infrastructure\EventNotification\AmqpEventSubscriber;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

class UserWasCreatedConsumerCommandFactory
{
    const QUEUE_NAME = 'UserWasCreated';
    const BINDING_KEYS = [
        UserWasCreated::class
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
        return new UserWasCreatedConsumerCommand($logger, $amqpEventSubscriber);
    }
}
