<?php


namespace Antevenio\DddExample\Infrastructure\EventStore\Prooph;

use Prooph\Common\Messaging\MessageFactory;
use Prooph\Common\Messaging\Message;
use Antevenio\DddExample\Domain\Event\DomainEvent;

class ProophDomainEventMessageFactory implements MessageFactory
{
    public function createMessageFromArray(string $messageName, array $messageData): Message
    {
        if (! \class_exists($messageName)) {
            throw new \UnexpectedValueException('Given message name is not a valid class: ' . (string) $messageName);
        }

        if (! \is_subclass_of($messageName, DomainEvent::class)) {
            throw new \UnexpectedValueException(\sprintf(
                'Message class %s is not a sub class of %s',
                $messageName,
                DomainEvent::class
            ));
        }

        if (! isset($messageData['message_name'])) {
            $messageData['message_name'] = $messageName;
        }

        if (! isset($messageData['uuid'])) {
            $messageData['uuid'] = Uuid::uuid4();
        }

        if (! isset($messageData['created_at'])) {
            $messageData['created_at'] = new DateTimeImmutable('now', new DateTimeZone('UTC'));
        }

        if (! isset($messageData['metadata'])) {
            $messageData['metadata'] = [];
        }

        return ProophDomainEventDecorator::fromArray($messageData);
    }
}
