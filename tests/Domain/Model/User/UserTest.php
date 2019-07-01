<?php


namespace Antevenio\DddExample\Domain\Model\User;

use Antevenio\DddExample\Domain\Event\CollectInMemoryDomainEventsSubscriber;
use Antevenio\DddExample\Domain\Event\DomainEventPublisher;
use Assert\AssertionFailedException;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    private $id = 'myId';
    private $email = 'john.doe@antevenio.com';

    public function testShouldBeCreated()
    {
        $user = User::create($this->email);

        $this->assertNotNull($user);
        $this->assertInstanceOf(User::class, $user);
        $this->assertNotEmpty($user->getId());
        $this->assertEquals($this->email, $user->getEmail());
    }

    /**
     * @dataProvider invalidEmailsDataProvider
     */
    public function testShouldNotBeCreatedWithAnInvalidEmail($email)
    {
        $this->expectException(AssertionFailedException::class);
        User::create($email);
    }

    public function invalidEmailsDataProvider()
    {
        return [
            [''],
            ['foo']
        ];
    }

    public function testShouldBeCreatedFromArray()
    {
        $user = User::fromArray([
            'id' => $this->id,
            'email' => $this->email
        ]);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($this->id, $user->getId());
        $this->assertEquals($this->email, $user->getEmail());
    }

    public function testShouldBeJsonSerialized()
    {
        $data = [
            'id' => $this->id,
            'email' => $this->email
        ];
        $user = User::fromArray($data);

        $this->assertEquals(json_encode($data), json_encode($user));
    }

    public function testShouldPublishUserWasCreatedEvent()
    {
        $collectInMemoryDomainEventsSubscribe = new CollectInMemoryDomainEventsSubscriber();
        DomainEventPublisher::instance()->subscribe($collectInMemoryDomainEventsSubscribe);

        $user = User::create($this->email);

        $event = current($collectInMemoryDomainEventsSubscribe->events());
        $this->assertInstanceOf(UserWasCreated::class, $event);
        $this->assertEquals($user->getId(), $event->getUserId());
        $this->assertEquals($user->getEmail(), $event->getEmail());

        DomainEventPublisher::instance()->unsubscribe($collectInMemoryDomainEventsSubscribe);
    }
}
