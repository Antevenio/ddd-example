<?php


namespace Antevenio\DddExample\Domain\Event;

use Antevenio\DddExample\Domain\ValueObject\Timestamp;

class FakeDomainEvent implements DomainEvent, PublishableDomainEvent
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var Timestamp
     */
    private $occurredOn;

    private function __construct($name, Timestamp $occurredOn)
    {
        $this->name = $name;
        $this->occurredOn = $occurredOn;
    }

    public static function create($name)
    {
        return new self($name, Timestamp::now());
    }

    public function occurredOn() : Timestamp
    {
        return $this->occurredOn;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    public static function fromArray($data)
    {
        return new self(
            $data['name'],
            Timestamp::fromString($data['occurredOn'])
        );
    }

    public function jsonSerialize()
    {
        return [
            'name' => $this->name,
            'occurredOn' => $this->occurredOn
        ];
    }
}
