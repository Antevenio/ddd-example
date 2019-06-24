<?php


namespace Antevenio\DddExample\Infrastructure\Ui\Console\Command;

use Antevenio\DddExample\Domain\Email\EmailAddress;
use Antevenio\DddExample\Domain\Email\EmailSendRequest;
use Antevenio\DddExample\Domain\Email\EmailService;
use Antevenio\DddExample\Infrastructure\EventNotification\AmqpEventSubscriber;
use Antevenio\DddExample\Domain\UserWasCreated;
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
     * @var EmailService
     */
    private $emailService;

    /**
     * UserWasCreatedConsumerCommand constructor.
     * @param LoggerInterface $logger
     * @param AmqpEventSubscriber $amqpEventSubscriber
     * @param EmailService $emailService
     */
    public function __construct(
        LoggerInterface $logger,
        AmqpEventSubscriber $amqpEventSubscriber,
        EmailService $emailService
    ) {
        $this->logger = $logger;
        $this->amqpEventSubscriber = $amqpEventSubscriber;
        $this->emailService = $emailService;
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
        $to = EmailAddress::create($userWasCreated->getEmail());
        $data = ['name' => 'FooName'];
        $emailSendRequest = new EmailSendRequest('example', 'es', [$to], $data);
        $this->emailService->send($emailSendRequest);

        $this->logger->debug('An email was send!');
    }
}
