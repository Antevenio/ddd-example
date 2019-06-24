<?php

namespace Antevenio\DddExample\Domain\Model\User;

interface UserRepository
{
    
    public function save(User $user) : void;

    public function fetchById($getId) : User;
}
