<?php


namespace Antevenio\DddExample\Infrastructure\Ui\Console\Command;

use Antevenio\DddExample\Infrastructure\EventNotification\AmqpEventSubscriber;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AllEventsConsumerCommand extends Command
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var AmqpEventSubscriber
     */
    private $amqpEventSubscriber;

    /**
     * AllEventsConsumerCommand constructor.
     * @param LoggerInterface $logger
     * @param AmqpEventSubscriber $amqpEventSubscriber
     */
    public function __construct(LoggerInterface $logger, AmqpEventSubscriber $amqpEventSubscriber)
    {
        $this->logger = $logger;
        $this->amqpEventSubscriber = $amqpEventSubscriber;
        parent::__construct();
    }


    protected function configure()
    {
        $this->setName('all-events-consumer')
            ->setDescription('Consume all events');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->amqpEventSubscriber->setCallback([$this, 'consume']);
        $this->amqpEventSubscriber->start();
    }
    
    public function consume($domainEvent)
    {
        $this->logger->debug('Doing things with all events...' . json_encode($domainEvent));
    }
}
