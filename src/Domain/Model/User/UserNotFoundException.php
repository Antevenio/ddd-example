<?php

namespace Antevenio\DddExample\Domain\Model\User;

use Exception;
use Throwable;

class UserNotFoundException extends \Exception
{

    public function __construct()
    {
        parent::__construct('User not found', 404);
    }
}
