<?php


namespace Antevenio\DddExample\Domain\Model\User;

use Antevenio\DddExample\Domain\Event\DomainEventPublisher;
use Assert\Assert;
use Ramsey\Uuid\Uuid;

class User implements \JsonSerializable
{

    private $id;
    private $email;

    /**
     * User constructor.
     * @param $email
     */
    private function __construct($id, $email)
    {
        $this->id = $id;
        $this->email = $email;
    }

    public static function create($email)
    {
        $id = Uuid::uuid1();
        $self = new self($id, $email);
        $self->validate();
        $userWasCreated = UserWasCreated::create($id, $email);

        DomainEventPublisher::instance()->publish($userWasCreated);

        return $self;
    }

    public static function fromArray($row)
    {
        return new self($row['id'], $row['email']);
    }

    public function validate()
    {
        Assert::lazy()
            ->that($this->email, 'email')->notEmpty()->email()
            ->verifyNow();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'email' => $this->email
        ];
    }
}
