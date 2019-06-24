<?php

namespace Antevenio\DddExample\Infrastructure\Ui\Http\Handlers;

use Antevenio\DddExample\Actions\ActionBus;
use Antevenio\DddExample\Application\Actions\CreateUserActionRequest;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CreateUserHandler implements RequestHandlerInterface
{
    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var ActionBus
     */
    private $actionBus;

    /**
     * MeHandler constructor.
     * @param ResponseFactoryInterface $responseFactory
     */
    public function __construct(ResponseFactoryInterface $responseFactory, ActionBus $actionBus)
    {
        $this->responseFactory = $responseFactory;
        $this->actionBus = $actionBus;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $requestParameters = (array) $request->getParsedBody();
        $createUserRequest = new CreateUserActionRequest($requestParameters['email']);
        $user = $this->actionBus->run($createUserRequest);
        $response = $this->responseFactory->createResponse(201);
        $response->getBody()->write(json_encode($user));

        return $response;
    }
}
