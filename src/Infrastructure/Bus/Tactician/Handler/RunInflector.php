<?php


namespace Antevenio\DddExample\Infrastructure\Bus\Tactician\Handler;

use League\Tactician\Handler\MethodNameInflector\MethodNameInflector;

class RunInflector implements MethodNameInflector
{
    public function inflect($command, $commandHandler)
    {
        return 'run';
    }
}
