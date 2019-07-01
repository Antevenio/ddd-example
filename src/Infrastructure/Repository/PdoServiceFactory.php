<?php


namespace Antevenio\DddExample\Infrastructure\Repository;

use Psr\Container\ContainerInterface;

class PdoServiceFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $boundedContextName = $container->get('config')['boundedContextName'];
        $configDb = $container->get('config')['db'][$boundedContextName];

        return (new PdoFactory)($configDb);
    }
}
