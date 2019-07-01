<?php


namespace Antevenio\DddExample\Application\Actions;

class CreateUserActionRequest
{
    /**
     * @var string
     */
    private $email;

    /**
     * CreateUserActionRequest constructor.
     * @param string $email
     */
    public function __construct(string $email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }
}
