<?php


namespace Antevenio\DddExample\Domain\Model\User;

use Antevenio\DddExample\Domain\Event\DomainEvent;
use Antevenio\DddExample\Domain\Event\PublishableDomainEvent;
use Antevenio\DddExample\Domain\ValueObject\Timestamp;

class UserWasRead implements DomainEvent, PublishableDomainEvent
{

    private $userId;
    
    /**
     * @var Timestamp
     */
    private $occurredOn;


    /**
     * UserWasRead constructor.
     * @param $userId
     * @param Timestamp $occurredOn
     */
    private function __construct($userId, Timestamp $occurredOn)
    {
        $this->userId = $userId;
        $this->occurredOn = $occurredOn;
    }

    public static function create($userId)
    {
        return new self(
            $userId,
            Timestamp::now()
        );
    }

    public function jsonSerialize()
    {
        return [
            'userId' => $this->userId,
            'occurredOn' => $this->occurredOn,
        ];
    }

    public static function fromArray(array $data)
    {
        return new self(
            $data['userId'],
            Timestamp::fromString($data['occurredOn'])
        );
    }

    public function occurredOn() : Timestamp
    {
        return $this->occurredOn;
    }
}
