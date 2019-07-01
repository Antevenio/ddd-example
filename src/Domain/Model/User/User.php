<?php


namespace Antevenio\DddExample\Domain\Model\User;

use Antevenio\DddExample\Domain\Event\DomainEventPublisher;
use Assert\AssertionFailedException;
use Ramsey\Uuid\Uuid;

class User implements \JsonSerializable
{

    /**
     * @var string
     */
    private $id;

    /**
     * @var Email
     */
    private $email;

    /**
     * User constructor.
     * @param string $id
     * @param Email $email
     */
    private function __construct(string $id, Email $email)
    {
        $this->id = $id;
        $this->email = $email;
    }

    /**
     * Creates a new user
     *
     * @param string $email
     * @return User
     * @throws AssertionFailedException if email is not valid
     */
    public static function create(string $email) : self
    {
        $id = Uuid::uuid1();
        $self = new self($id, Email::create($email));
        $userWasCreated = UserWasCreated::create($id, $email);

        DomainEventPublisher::instance()->publish($userWasCreated);

        return $self;
    }

    /**
     * Returns a User from raw data
     *
     * @param array $data
     * @return User
     * @throws AssertionFailedException if email is not valid
     */
    public static function fromArray(array $data) : self
    {
        return new self($data['id'], Email::create($data['email']));
    }

    public function getId() : string
    {
        return $this->id;
    }

    public function getEmail() : Email
    {
        return $this->email;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'email' => (string) $this->email
        ];
    }
}
