<?php


namespace Antevenio\DddExample\Infrastructure\Bus\Tactician;

use Antevenio\DddExample\Domain\Event\EventStore;
use Antevenio\DddExample\Infrastructure\Bus\Tactician\Handler\RunInflector;
use Antevenio\DddExample\Infrastructure\Bus\Tactician\Middlewares\AppendDomainEventsToStoreMiddleware;
use Antevenio\DddExample\Infrastructure\Bus\Tactician\Middlewares\PdoTransactionMiddleware;
use League\Tactician\Container\ContainerLocator;
use League\Tactician\CommandBus;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Logger\Formatter\ClassPropertiesFormatter;
use League\Tactician\Logger\LoggerMiddleware;
use League\Tactician\Plugins\LockingMiddleware;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

class TacticianActionBusFactory
{
    /**
     * @var string
     */
    private $pdoServiceName;

    /**
     * @var array
     */
    private $actionRequestToActionMap;


    /**
     * TacticianActionBusFactory constructor.
     * @param $pdoServiceName
     */
    public function __construct($pdoServiceName, array $actionRequestToActionMap)
    {
        $this->pdoServiceName = $pdoServiceName;
        $this->actionRequestToActionMap = $actionRequestToActionMap;
    }

    public function __invoke(ContainerInterface $container)
    {

        $containerLocator = new ContainerLocator(
            $container,
            $this->actionRequestToActionMap
        );

        $tactician = new CommandBus([
            new LockingMiddleware(),
            new LoggerMiddleware(new ClassPropertiesFormatter(), $container->get(LoggerInterface::class)),
            new PdoTransactionMiddleware($container->get($this->pdoServiceName)),
            new AppendDomainEventsToStoreMiddleware($container->get(EventStore::class)),
            new CommandHandlerMiddleware(
                new ClassNameExtractor(),
                $containerLocator,
                new RunInflector()
            )
        ]);
        return new TacticianActionBus($tactician);
    }
}
