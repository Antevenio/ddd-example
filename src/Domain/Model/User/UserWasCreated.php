<?php

namespace Antevenio\DddExample\Domain\Model\User;

use Antevenio\DddExample\Domain\Event\DomainEvent;
use Antevenio\DddExample\Domain\Event\PublishableDomainEvent;
use Antevenio\DddExample\Domain\ValueObject\Timestamp;

class UserWasCreated implements DomainEvent, PublishableDomainEvent
{
    /**
     * @var string
     */
    private $userId;

    /**
     * @var string
     */
    private $email;

    /**
     * @var Timestamp
     */
    private $occurredOn;

    /**
     * UserWasCreated constructor.
     * @param string $userId
     * @param string $email
     * @param Timestamp $occurredOn
     */
    public function __construct(string $userId, string $email, Timestamp $occurredOn)
    {
        $this->userId = $userId;
        $this->email = $email;
        $this->occurredOn = $occurredOn;
    }

    public static function create(string $userId, string $email)
    {
        return new self(
            $userId,
            $email,
            Timestamp::now()
        );
    }

    public static function fromArray(array $data)
    {
        return new self(
            $data['userId'],
            $data['email'],
            Timestamp::fromString($data['occurredOn'])
        );
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->userId;
    }

    /**
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    public function jsonSerialize()
    {
        return [
            'userId' => $this->userId,
            'email' => $this->email,
            'occurredOn' => $this->occurredOn,
        ];
    }

    public function occurredOn(): Timestamp
    {
        return $this->occurredOn;
    }
}
