<?php

namespace Antevenio\DddExample\Domain\Event;

use PHPUnit\Framework\TestCase;
use Antevenio\DddExample\Domain\Event\DomainEventPublisherCloningIsNotAllowedException;

class DomainEventPublisherTest extends TestCase
{
    /**
     * @var DomainEventPublisher
     */
    private $domainEventPublisher;

    protected function setUp(): void
    {
        parent::setUp();
        $this->domainEventPublisher = DomainEventPublisher::instance();
        $this->domainEventPublisher->unsubscribeAll();
    }

    public function testShouldNotifySubscriber()
    {
        $this->subscribe($subscriber = new SpySubscriber('test-event'));
        $this->publish($domainEvent = FakeDomainEvent::create('test-event'));

        $this->assertEventHandled($subscriber, $domainEvent);
    }

    public function testSubscribersShouldNotBeNotified()
    {
        $this->subscribe($subscriber = new SpySubscriber('test-event'));
        $this->publish(FakeDomainEvent::create('other-test-event'));

        $this->assertEventNotHandled($subscriber);
    }

    public function testShouldUnsubscribeSubscriber()
    {
        $domainEvent = FakeDomainEvent::create('test-event');
        $subscriber = new SpySubscriber('test-event');
        $this->subscribe($subscriber);
        $this->unsubscribe($subscriber);
        $this->publish($domainEvent);
        $this->assertEventNotHandled($subscriber);
    }

    public function testCloneShouldThrowCloningIsNotAllowedException()
    {
        $this->expectException(DomainEventPublisherCloningIsNotAllowedException::class);
        clone $this->domainEventPublisher;
    }

    private function subscribe($subscriber)
    {
        return $this->domainEventPublisher->subscribe($subscriber);
    }

    private function publish($domainEvent)
    {
        $this->domainEventPublisher->publish($domainEvent);
    }

    private function assertEventHandled($subscriber, $domainEvent)
    {
        $this->assertTrue($subscriber->isHandled);
        $this->assertEquals($domainEvent, $subscriber->domainEvent);
    }

    private function assertEventNotHandled($subscriber)
    {
        $this->assertFalse($subscriber->isHandled);
        $this->assertNull($subscriber->domainEvent);
    }

    private function unsubscribe($subscriber)
    {
        $this->domainEventPublisher->unsubscribe($subscriber);
    }
}
