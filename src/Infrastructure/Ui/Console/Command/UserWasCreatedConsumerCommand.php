<?php


namespace Antevenio\DddExample\Infrastructure\Ui\Console\Command;

use Antevenio\DddExample\Domain\Model\User\UserWasCreated;
use Antevenio\DddExample\Infrastructure\EventNotification\AmqpEventSubscriber;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UserWasCreatedConsumerCommand extends Command
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
     * UserWasCreatedConsumerCommand constructor.
     * @param LoggerInterface $logger
     * @param AmqpEventSubscriber $amqpEventSubscriber
     */
    public function __construct(
        LoggerInterface $logger,
        AmqpEventSubscriber $amqpEventSubscriber
    ) {
        $this->logger = $logger;
        $this->amqpEventSubscriber = $amqpEventSubscriber;
        parent::__construct();
    }


    protected function configure()
    {
        $this->setName('user-was-created-consumer')
            ->setDescription('Consume UserWasCreated events');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->amqpEventSubscriber->setCallback([$this, 'consume']);
        $this->amqpEventSubscriber->start();
    }
    
    public function consume(UserWasCreated $userWasCreated)
    {
        $this->logger->debug('Doing things with UserWasCreated...' . json_encode($userWasCreated));
    }
}
