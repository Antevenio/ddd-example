<?php


namespace Antevenio\DddExample\Domain\Event;

use PHPUnit\Framework\TestCase;

class CollectInMemoryDomainEventsSubscriberTest extends TestCase
{

    /**
     * @var CollectInMemoryDomainEventsSubscriber
     */
    private $collectInMemoryDomainEventsSubscriber;

    protected function setUp(): void
    {
        parent::setUp();
        $this->collectInMemoryDomainEventsSubscriber = new CollectInMemoryDomainEventsSubscriber();
    }

    public function testShouldBeCreated()
    {
        $this->assertNotNull($this->collectInMemoryDomainEventsSubscriber);
    }

    public function testShouldCollectDomainEvents()
    {
        $domainEventPublisher = DomainEventPublisher::instance();
        $domainEventPublisher->subscribe($this->collectInMemoryDomainEventsSubscriber);
        $fakeEvent = FakeDomainEvent::create('foo');
        $domainEventPublisher->publish($fakeEvent);

        $this->assertEquals(
            [$fakeEvent],
            $this->collectInMemoryDomainEventsSubscriber->events()
        );
    }
}
