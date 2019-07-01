<?php


namespace Antevenio\DddExample\Infrastructure\EventNotification;

use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;

class AmqpEventSubscriber
{
    const EXCHANGE = 'example_topic_exchange';
    const PREFETCH_SIZE_NO_LIMIT = null;
    const PREFETCH_COUNT = 1;
    const GLOBAL_SETTINGS_PER_CONSUMER = null;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var array
     */
    private $config;


    /**
     * @var string
     */
    private $queueName;

    /**
     * @var array
     */
    private $bindingKeys;

    /**
     * @var callable
     */
    private $callback;

    public function __construct(
        LoggerInterface $logger,
        array $config,
        string $queueName,
        array $bindingKeys,
        callable $callback = null
    ) {
        $this->logger = $logger;
        $this->config = $config;
        $this->queueName = $queueName;
        $this->bindingKeys = $bindingKeys;
        $this->callback = $callback;
    }

    /**
     * @param callable $callback
     */
    public function setCallback(callable $callback): void
    {
        $this->callback = $callback;
    }

    public function start() : void
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
        $channel->basic_qos(
            self::PREFETCH_SIZE_NO_LIMIT,
            self::PREFETCH_COUNT,
            self::GLOBAL_SETTINGS_PER_CONSUMER
        );
        $channel->queue_declare($this->queueName, false, true, false, false);

        foreach ($this->bindingKeys as $bindingKey) {
            $channel->queue_bind($this->queueName, self::EXCHANGE, $bindingKey);
        }

        $channel->basic_consume($this->queueName, '', false, false, false, false, [$this, 'consume']);

        while (count($channel->callbacks)) {
            $channel->wait();
        }

        $channel->close();
        $connection->close();
    }

    public function consume(AMQPMessage $message) : void
    {
        $routingKey = $message->delivery_info['routing_key'];
        $domainEvent = (new AmqpMessageDomainEventConverter)($message);
        $jsonDomainEvent = json_encode($domainEvent);

        $this->logger->debug("Consuming Event Start: routing_key: $routingKey, domainEvent: $jsonDomainEvent");
        call_user_func($this->callback, $domainEvent);
        $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
        $this->logger->debug("Consuming Event End: routing_key: $routingKey, domainEvent: $jsonDomainEvent");
    }
}
