<?php

namespace Antevenio\DddExample\Infrastructure\Ui\Http\Handlers;

use Antevenio\DddExample\Application\ActionBus;
use Antevenio\DddExample\Application\Actions\GetUserActionRequest;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class GetUserHandler implements RequestHandlerInterface
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
        $requestParameters = $request->getQueryParams();
        $createUserRequest = new GetUserActionRequest($requestParameters['id']);
        $user = $this->actionBus->run($createUserRequest);
        $response = $this->responseFactory->createResponse(200);
        $response->getBody()->write(json_encode($user));

        return $response;
    }
}
