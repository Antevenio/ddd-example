<?php


namespace Antevenio\DddExample\Domain\ValueObject;

use Assert\Assert;

class Email
{

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
        Assert::lazy()
            ->that($email, 'email')->notEmpty()->email()
            ->verifyNow();
        $this->email = $email;
    }

    public static function create(string $email)
    {
        return new self($email);
    }

    public function __toString()
    {
        return $this->email;
    }
}
