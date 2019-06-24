<?php


namespace Antevenio\DddExample\Infrastructure\Ui\Console\Command;

use Psr\Container\ContainerInterface;

class HelloWorldCommandFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new HelloWorldCommand();
    }
}
