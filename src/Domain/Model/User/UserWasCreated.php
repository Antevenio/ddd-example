<?php

namespace Antevenio\DddExample\Domain\Model\User;

use Antevenio\DddExample\Domain\Event\DomainEvent;
use Antevenio\DddExample\Domain\Event\PublishableDomainEvent;
use Antevenio\DddExample\Domain\ValueObject\Timestamp;

class UserWasCreated implements DomainEvent, PublishableDomainEvent
{
    private $userId;
    
    private $email;

    /**
     * @var Timestamp
     */
    private $occurredOn;


    /**
     * UserWasCreated constructor.
     * @param $userId
     * @param $email
     * @param Timestamp $occurredOn
     */
    private function __construct($userId, $email, Timestamp $occurredOn)
    {
        $this->userId = $userId;
        $this->email = $email;
        $this->occurredOn = $occurredOn;
    }

    public static function create($userId, $email)
    {
        return new self(
            $userId,
            $email,
            Timestamp::now()
        );
    }

    /**
     * @return string
     */
    public function getEmail() : string
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

    public static function fromArray(array $data)
    {
        return new self(
            $data['userId'],
            $data['email'],
            Timestamp::fromString($data['occurredOn'])
        );
    }

    public function occurredOn() : Timestamp
    {
        return $this->occurredOn;
    }
}
