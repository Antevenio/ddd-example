<?php


namespace Antevenio\DddExample\Infrastructure\Ui\Console\Command;

use Antevenio\DddExample\Domain\Event\EventStore;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EventNotificationCommand extends Command
{

    /**
     * @var EventStore
     */
    private $eventStore;

    /**
     * NotifyEventsCommand constructor.
     * @param EventStore $eventStore
     */
    public function __construct(
        EventStore $eventStore
    ) {
        $this->eventStore = $eventStore;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('notify-events')
            ->setDescription('Notify events to Rabbitmq');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->eventStore->notifyStoredEvents();
    }
}
