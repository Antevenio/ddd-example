<?php


namespace Antevenio\DddExample\Infrastructure;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;

class LoggerFactory
{

    public function __invoke(ContainerInterface $container)
    {
        $configLogger = $container->get('config')['logger'];
        $logger = new Logger($configLogger['name']);
        $logger->pushProcessor(new UidProcessor());
        $logger->pushHandler(new StreamHandler($configLogger['path'], $configLogger['level']));
        return $logger;
    }
}
