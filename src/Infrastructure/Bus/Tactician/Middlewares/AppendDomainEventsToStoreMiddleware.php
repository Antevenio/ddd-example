<?php


namespace Antevenio\DddExample\Infrastructure\Bus\Tactician\Middlewares;

use Antevenio\DddExample\Domain\Event\CollectInMemoryDomainEventsSubscriber;
use Antevenio\DddExample\Domain\Event\DomainEventPublisher;
use Antevenio\DddExample\Domain\Event\EventStore;

use League\Tactician\Middleware;

class AppendDomainEventsToStoreMiddleware implements Middleware
{
    /**
     * @var EventStore
     */
    private $eventStore;

    public function __construct(EventStore $eventStore)
    {
        $this->eventStore = $eventStore;
    }

    public function execute($command, callable $next)
    {
        $collectDomainEventsSubscriber = new CollectInMemoryDomainEventsSubscriber();
        DomainEventPublisher::instance()->subscribe($collectDomainEventsSubscriber);

        $returnValue = $next($command);

        $publishableEvents = $collectDomainEventsSubscriber->events();
        foreach ($publishableEvents as $publishableEvent) {
            $this->eventStore->append($publishableEvent);
        }

        return $returnValue;
    }
}
