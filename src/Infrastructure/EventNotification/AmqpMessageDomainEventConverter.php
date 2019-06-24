<?php


namespace Antevenio\DddExample\Infrastructure\EventNotification;

use Antevenio\DddExample\Domain\Event\GenericDomainEvent;
use PhpAmqpLib\Message\AMQPMessage;

class AmqpMessageDomainEventConverter
{

    public function __invoke(AmqpMessage $amqpMessage)
    {
        $domainEventClass = $amqpMessage->delivery_info['routing_key'];
        $data = json_decode($amqpMessage->getBody(), true);

        return $this->createDomainEvent($domainEventClass, $data);
    }

    private function createDomainEvent($domainEventClass, $data)
    {
        return class_exists($domainEventClass) ?
            $domainEventClass::fromArray($data) :
            $domainEvent = GenericDomainEvent::fromArray($data);
    }
}
