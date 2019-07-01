<?php


namespace Antevenio\DddExample\Domain\Model\User;

use Assert\Assertion;

class Email
{

    const PROPERTY_NAME = 'email';
    /**
     * @var string
     */
    private $email;

    /**
     * Email constructor.
     * @param string $email
     */
    private function __construct(string $email)
    {
        Assertion::email($email);
        $this->email = $email;
    }

    /**
     * @param string $email
     * @return Email
     */
    public static function create(string $email)
    {
        return new self($email);
    }

    public function __toString()
    {
        return $this->email;
    }
}
