<?php


namespace Antevenio\DddExample\Application\Actions;

class CreateUserActionRequest
{
    private $email;

    /**
     * CreateUserRequest constructor.
     * @param $email
     */
    public function __construct($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }
}
