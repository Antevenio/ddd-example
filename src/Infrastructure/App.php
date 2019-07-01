<?php

namespace Antevenio\DddExample\Infrastructure;

use Antevenio\DddExample\Domain\Metric\MetricService;
use Antevenio\DddExample\Infrastructure\Domain\Metric\PrometheusMetricServiceFactory;
use Antevenio\DddExample\Infrastructure\Ui\Console\Command\CreateUserCommand;
use Antevenio\DddExample\Infrastructure\Ui\Console\Command\CreateUserCommandFactory;
use Antevenio\DddExample\Infrastructure\Ui\Http\Handlers\MetricsHandler;
use Antevenio\DddExample\Infrastructure\Ui\Http\Handlers\MetricsHandlerFactory;
use Antevenio\DddExample\Infrastructure\Ui\Http\Middlewares\CorsMiddleware;
use Antevenio\DddExample\Infrastructure\Ui\Http\Middlewares\LatencyMetricMiddelwareFactory;
use Antevenio\DddExample\Infrastructure\Ui\Http\Middlewares\LatencyMetricMiddleware;
use Bnf\Slim3Psr15\CallableResolver;
use Antevenio\DddExample\Infrastructure\Domain\Model\User\PdoUserRepository;
use Antevenio\DddExample\Infrastructure\Email\EmailServiceFactory;
use Antevenio\DddExample\Infrastructure\Repository\PdoServiceFactory;
use Antevenio\DddExample\Infrastructure\Ui\Http\Handlers\ErrorApiProblemHandlerFactory;
use Antevenio\DddExample\Infrastructure\Ui\Http\Middlewares\LoggerMiddleware;
use Antevenio\DddExample\Infrastructure\Ui\Http\Middlewares\LoggerMiddlewareFactory;
use Antevenio\DddExample\Application\Actions\CreateUserActionRequest;
use Antevenio\DddExample\Application\Actions\CreateUserAction;
use Antevenio\DddExample\Application\Actions\GetUserActionRequest;
use Antevenio\DddExample\Application\Actions\GetUserAction;
use Antevenio\DddExample\Infrastructure\Repository\PdoExampleService;
use Antevenio\DddExample\Infrastructure\Ui\Console\Command\AllEventsConsumerCommand;
use Antevenio\DddExample\Infrastructure\Ui\Console\Command\AllEventsConsumerCommandFactory;
use Antevenio\DddExample\Infrastructure\Ui\Console\Command\GetUserCommand;
use Antevenio\DddExample\Infrastructure\Ui\Console\Command\GetUserCommandFactory;
use Antevenio\DddExample\Infrastructure\Ui\Console\Command\HelloWorldCommand;
use Antevenio\DddExample\Infrastructure\Ui\Console\Command\HelloWorldCommandFactory;
use Antevenio\DddExample\Infrastructure\Ui\Console\Command\UserWasCreatedConsumerCommand;
use Antevenio\DddExample\Infrastructure\Ui\Console\Command\UserWasCreatedConsumerCommandFactory;
use Antevenio\DddExample\Infrastructure\Ui\Http\Handlers\CreateUserHandler;
use Antevenio\DddExample\Infrastructure\Ui\Http\Handlers\CreateUserHandlerFactory;
use Antevenio\DddExample\Infrastructure\Ui\Http\Handlers\GetUserHandler;
use Antevenio\DddExample\Infrastructure\Ui\Http\Handlers\GetUserHandlerFactory;
use Http\Factory\Slim\ResponseFactory;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Log\LoggerInterface;
use Zend\Config\Factory;

class App
{

    private $app;

    public function __construct($config = null)
    {
        if (!$config) {
            $config = $this->getConfig();
        }
        $dependencies = $this->getDependencies($config);
        $this->app = new \Slim\App($dependencies);
        $this->addRoutes();
    }

    private function getConfig()
    {
        return Factory::fromFiles(
            glob(sprintf(__DIR__ . '/../../config/{,*.}{global,%s,local}.php', APPLICATION_ENV), GLOB_BRACE)
        );
    }

    private function getDependencies($config): array
    {
        return array_merge(
            $this->getGlobalConfig($config),
            [PdoExampleService::class => new PdoServiceFactory()],
            $this->getActions(),
            EventConfigProvider::getConfig(
                PdoExampleService::class,
                $this->getActionRequestToActionMap()
            ),
            $this->getRepositories(),
            $this->getHttpHandlers(),
            $this->getConsoleCommands()
        );
    }

    private function getGlobalConfig($config)
    {
        return [
            'settings' => $config['settings'],
            'config' => $config,
            'errorHandler' => new ErrorApiProblemHandlerFactory(),
            LoggerInterface::class => new LoggerFactory(),
            ResponseFactoryInterface::class => new ResponseFactory(),
            'callableResolver' => function (ContainerInterface $container) {
                return new CallableResolver($container);
            },
            CorsMiddleware::class => function (ContainerInterface $container) {
                return new CorsMiddleware();
            },
            LoggerMiddleware::class => new LoggerMiddlewareFactory(),
            MetricService::class => new PrometheusMetricServiceFactory(),
            LatencyMetricMiddleware::class => new LatencyMetricMiddelwareFactory(),
        ];
    }

    private function getActions(): array
    {
        return [
            CreateUserAction::class => function (ContainerInterface $container) {
                return new CreateUserAction($container->get(UserRepository::class));
            },
            GetUserAction::class => function (ContainerInterface $container) {
                return new GetUserAction($container->get(UserRepository::class));
            },
        ];
    }

    private function getActionRequestToActionMap()
    {
        return [
            CreateUserActionRequest::class => CreateUserAction::class,
            GetUserActionRequest::class => GetUserAction::class,
        ];
    }


    private function getRepositories(): array
    {
        return [
            UserRepository::class => function (ContainerInterface $container) {
                return new PdoUserRepository($container->get(PdoExampleService::class));
            },
        ];
    }


    private function getHttpHandlers(): array
    {
        return [
            CreateUserHandler::class => new CreateUserHandlerFactory(),
            GetUserHandler::class => new GetUserHandlerFactory(),
            MetricsHandler::class => new MetricsHandlerFactory()
        ];
    }

    private function getConsoleCommands(): array
    {
        return [
            HelloWorldCommand::class => new HelloWorldCommandFactory(),
            GetUserCommand::class => new GetUserCommandFactory(),
            CreateUserCommand::class => new CreateUserCommandFactory(),
            UserWasCreatedConsumerCommand::class => new UserWasCreatedConsumerCommandFactory(),
            AllEventsConsumerCommand::class => new AllEventsConsumerCommandFactory(),
        ];
    }

    private function addRoutes(): void
    {
        $this->app->add(CorsMiddleware::class);

        $this->app->get('/hello', function ($request, $response, array $args) {
            $response->write('Hello, world!');
            return $response;
        })->add(LatencyMetricMiddleware::class);

        $this->app->post('/user', CreateUserHandler::class)
            ->add(LoggerMiddleware::class)
            ->add(LatencyMetricMiddleware::class);

        $this->app->get('/user', GetUserHandler::class)
            ->add(LoggerMiddleware::class)
            ->add(LatencyMetricMiddleware::class);

        $this->app->get('/metrics', MetricsHandler::class);
    }

    public function getInstance()
    {
        return $this->app;
    }
}
