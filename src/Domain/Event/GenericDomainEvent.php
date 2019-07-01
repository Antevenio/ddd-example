<?php


namespace Antevenio\DddExample\Domain\Event;

use Antevenio\DddExample\Domain\ValueObject\Timestamp;

class GenericDomainEvent implements DomainEvent
{
    /**
     * @var array
     */
    private $data;

    /**
     * @var Timestamp
     */
    private $occurredOn;

    /**
     * GenericDomainEvent constructor.
     * @param array $data
     */
    private function __construct(array $data, Timestamp $occurredOn)
    {
        $this->data = $data;
        $this->occurredOn = $occurredOn;
    }


    public static function fromArray(array $data)
    {
        return new self(
            $data,
            Timestamp::fromString($data['occurredOn'])
        );
    }

    public static function create(array $data)
    {
        return new self($data, Timestamp::now());
    }

    public function occurredOn(): Timestamp
    {
        return $this->occurredOn;
    }

    public function jsonSerialize()
    {
        return $this->data;
    }
}
