<?php


namespace Antevenio\DddExample\Application\Actions;

use Antevenio\DddExample\Domain\Event\CollectInMemoryDomainEventsSubscriber;
use Antevenio\DddExample\Domain\Event\DomainEventPublisher;
use Antevenio\DddExample\Domain\Model\User\UserNotFoundException;
use Antevenio\DddExample\Domain\Model\User\UserWasRead;
use Antevenio\DddExample\Infrastructure\Domain\Model\User\MemoryUserRepository;
use PHPUnit\Framework\TestCase;

class GetUserActionTest extends TestCase
{
    private $id = 'myid';
    private $email = 'john.doe@antevenio.com';


    /**
     * @var MemoryUserRepository
     */
    private $userRepository;

    /**
     * @var GetUserAction
     */
    private $getUserAction;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepository = new MemoryUserRepository([
            [
                'id' => $this->id,
                'email' => $this->email
            ]
        ]);
        $this->getUserAction = new GetUserAction($this->userRepository);
    }

    public function testShouldBeCreated()
    {
        $this->assertNotNull($this->userRepository);
    }

    public function testShouldGetAUser()
    {
        $getUserActionRequest = new GetUserActionRequest($this->id);
        $user = $this->getUserAction->run($getUserActionRequest);
        $this->assertEquals($this->id, $user->getId());
        $this->assertEquals($this->email, $user->getEmail());
    }

    public function testShouldThrowUserNotFoundException()
    {
        $getUserActionRequest = new GetUserActionRequest('foo');
        $this->expectException(UserNotFoundException::class);
        $this->getUserAction->run($getUserActionRequest);
    }

    public function testShouldPublishUserWasReadEvent()
    {
        $collectInMemoryDomainEventsSubscribe = new CollectInMemoryDomainEventsSubscriber();
        DomainEventPublisher::instance()->subscribe($collectInMemoryDomainEventsSubscribe);

        $getUserActionRequest = new GetUserActionRequest($this->id);
        $user = $this->getUserAction->run($getUserActionRequest);

        $event = current($collectInMemoryDomainEventsSubscribe->events());
        $this->assertInstanceOf(UserWasRead::class, $event);
        $this->assertEquals($user->getId(), $event->getUserId());

        DomainEventPublisher::instance()->unsubscribe($collectInMemoryDomainEventsSubscribe);
    }
}
