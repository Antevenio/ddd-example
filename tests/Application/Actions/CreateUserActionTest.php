<?php


namespace Antevenio\DddExample\Application\Actions;

use Antevenio\DddExample\Infrastructure\Domain\Model\User\MemoryUserRepository;
use PHPUnit\Framework\TestCase;

class CreateUserActionTest extends TestCase
{
    private $email = 'john.doe@antevenio.com';
    /**
     * @var MemoryUserRepository
     */
    private $userRepository;
    /**
     * @var CreateUserAction
     */
    private $createUserAction;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepository = new MemoryUserRepository([]);
        $this->createUserAction = new CreateUserAction(
            $this->userRepository
        );
    }

    public function testShouldBeCreated()
    {
        $this->assertNotNull($this->createUserAction);
    }

    public function testShouldCreateAUser()
    {
        $createUserActionRequest = new CreateUserActionRequest($this->email);
        $result = $this->createUserAction->run($createUserActionRequest);

        $this->assertEquals($this->email, $result->getEmail());
        $savedUsers = $this->userRepository->getUsers();
        $this->assertCount(1, $savedUsers);
        $this->assertEquals($this->email, current($savedUsers)['email']);
    }
}
