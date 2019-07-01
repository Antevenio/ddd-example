<?php


namespace Antevenio\DddExample\Domain\Model\User;

use Antevenio\DddExample\Domain\Event\DomainEvent;
use Antevenio\DddExample\Domain\Event\PublishableDomainEvent;
use Antevenio\DddExample\Domain\ValueObject\Timestamp;

class UserWasRead implements DomainEvent, PublishableDomainEvent
{

    /**
     * @var string
     */
    private $userId;
    
    /**
     * @var Timestamp
     */
    private $occurredOn;


    /**
     * UserWasRead constructor.
     * @param string $userId
     * @param Timestamp $occurredOn
     */
    private function __construct(string $userId, Timestamp $occurredOn)
    {
        $this->userId = $userId;
        $this->occurredOn = $occurredOn;
    }

    public static function create(string $userId)
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

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->userId;
    }

    public function occurredOn() : Timestamp
    {
        return $this->occurredOn;
    }
}
