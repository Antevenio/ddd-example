<?php


namespace Antevenio\DddExample\Domain\Event;

use PHPUnit\Framework\TestCase;

class FakeDomainEventTest extends TestCase
{

    /**
     * @var FakeDomainEvent
     */
    private $fakeDomainEvent;

    protected function setUp(): void
    {
        parent::setUp();
        $this->fakeDomainEvent = FakeDomainEvent::create('foo');
    }

    public function testShouldBeCreated()
    {
        $this->assertNotNull($this->fakeDomainEvent);
    }

    public function testShouldBeSerializedToJson()
    {
        $jsonDomainEvent = json_encode($this->fakeDomainEvent);
        $this->assertNotNull($jsonDomainEvent);
    }

    public function testShouldBeSerializedAndDeserializedFromJson()
    {
        $jsonSerializedFakeDomainEvent = json_encode($this->fakeDomainEvent);
        $fakeDomainEventArray = json_decode($jsonSerializedFakeDomainEvent, true);
        $fakeDomainFromArray = FakeDomainEvent::fromArray($fakeDomainEventArray);
        $this->assertEquals($jsonSerializedFakeDomainEvent, json_encode($fakeDomainFromArray));
    }
}
