<?php


namespace Antevenio\DddExample\Infrastructure\EventNotification;

use Antevenio\DddExample\Domain\Event\DomainEvent;
use Antevenio\DddExample\Domain\Event\EventNotification;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;

class AmqpEventNotification implements EventNotification
{
    const EXCHANGE = 'example_topic_exchange';
    const MESSAGE_PROPERTIES = ['content_type' => 'text/plain'];

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var array
     */
    private $config;

    /**
     * AmqpEventNotification constructor.
     * @param LoggerInterface $logger
     * @param array $config
     */
    public function __construct(LoggerInterface $logger, array $config)
    {
        $this->logger = $logger;
        $this->config = $config;
    }

    public function notify(DomainEvent $domainEvent): void
    {
        $connection = (new AmqpConnectionFactory)($this->config);
        $channel = $connection->channel();
        $channel->exchange_declare(
            self::EXCHANGE,
            AMQPExchangeType::TOPIC,
            false,
            true,
            false
        );
        $jsonDomainEvent = json_encode($domainEvent);
        $message = new AMQPMessage(
            $jsonDomainEvent,
            self::MESSAGE_PROPERTIES
        );
        $routingKey = get_class($domainEvent);
        $channel->basic_publish(
            $message,
            self::EXCHANGE,
            $routingKey
        );
        $this->logger->debug("Event notified: routing_key: $routingKey, domainEvent: $jsonDomainEvent");
        $channel->close();
        $connection->close();
    }
}
