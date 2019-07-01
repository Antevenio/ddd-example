<?php


namespace Antevenio\DddExample\Infrastructure\Ui\Console;

use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Application as ConsoleApplication;
use Symfony\Component\Console\CommandLoader\ContainerCommandLoader;

class App
{

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var array
     */
    private $commandMap;

    /**
     * ConsoleApplication
     */
    private $consoleApplication;

    /**
     * Application constructor.
     * @param ContainerInterface $container
     * @param array $commandMap
     */
    public function __construct(ContainerInterface $container, array $commandMap)
    {
        $this->container = $container;
        $this->commandMap = $commandMap;
        $commandLoader = new ContainerCommandLoader($container, $commandMap);
        $this->consoleApplication = new ConsoleApplication();
        $this->consoleApplication->setCommandLoader($commandLoader);
    }


    public function run()
    {
        $this->consoleApplication->run();
    }
}
