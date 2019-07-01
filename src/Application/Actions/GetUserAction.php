<?php


namespace Antevenio\DddExample\Application\Actions;

use Antevenio\DddExample\Domain\Event\DomainEventPublisher;
use Antevenio\DddExample\Domain\Model\User\User;
use Antevenio\DddExample\Domain\Model\User\UserRepository;
use Antevenio\DddExample\Domain\Model\User\UserWasRead;

class GetUserAction
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
     * @param GetUserActionRequest $getUserActionRequest
     * @return User
     */
    public function run(GetUserActionRequest $getUserActionRequest)
    {
        $user = $this->userRepository->fetchById($getUserActionRequest->getId());
        DomainEventPublisher::instance()->publish(UserWasRead::create($user->getId()));
        return $user;
    }
}
