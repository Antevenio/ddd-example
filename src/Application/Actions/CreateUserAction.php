<?php


namespace Antevenio\DddExample\Application\Actions;

use Antevenio\DddExample\Application\Actions\Action;
use Antevenio\DddExample\Domain\User;
use Antevenio\DddExample\Domain\UserRepository;

class CreateUserAction
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * CreateUserAction constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }


    /**
     * @param CreateUserActionRequest $createUserRequest
     * @return User|mixed
     */
    public function run($createUserRequest)
    {
        $user = User::create($createUserRequest->getEmail());
        $this->userRepository->save($user);
        return $user;
    }
}
