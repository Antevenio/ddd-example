<?php

namespace Antevenio\DddExample\Domain\Event;

use Antevenio\DddExample\Domain\ValueObject\Timestamp;

interface DomainEvent extends \JsonSerializable
{
    /**
     * @return Timestamp
     */
    public function occurredOn() : Timestamp;

    public static function fromArray(array $data);
}
