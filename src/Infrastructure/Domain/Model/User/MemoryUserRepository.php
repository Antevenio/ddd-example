<?php


namespace Antevenio\DddExample\Infrastructure\Domain\Model\User;

use Antevenio\DddExample\Domain\Model\User\User;
use Antevenio\DddExample\Domain\Model\User\UserNotFoundException;
use Antevenio\DddExample\Domain\Model\User\UserRepository;

class MemoryUserRepository implements UserRepository
{
    /**
     * @var array
     */
    private $users;

    /**
     * MemoryUserRepository constructor.
     * @param array
     */
    public function __construct(array $users)
    {
        foreach ($users as $user) {
            $this->users[$user['id']] = $user;
        }
    }

    public function save(User $user): void
    {
        $this->users[$user->getId()] = [
            'id' => $user->getId(),
            'email' => (string)$user->getEmail()
        ];
    }

    public function fetchById($id): User
    {
        $user = array_key_exists($id, $this->users) ? $this->users[$id] : null;
        if (empty($user)) {
            throw new UserNotFoundException();
        }
        return User::fromArray($user);
    }

    public function getUsers(): array
    {
        return $this->users;
    }
}
