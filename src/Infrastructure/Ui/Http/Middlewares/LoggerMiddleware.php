<?php

namespace Antevenio\DddExample\Infrastructure\Ui\Http\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

class LoggerMiddleware implements MiddlewareInterface
{

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * LoggerMiddleware constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Process an incoming server request and it logs request/response.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);
        $this->logger->debug('Request/Response', [
            'request' => $this->getParsedRequest($request),
            'response' => $this->getParsedResponse($response)
        ]);
        return $response;
    }

    private function getParsedRequest(ServerRequestInterface $request)
    {
        return [
            'method' => $request->getMethod(),
            'request_target' => $request->getRequestTarget(),
            'uri' => (string)$request->getUri(),
            'protocol_version' => $request->getProtocolVersion(),
            'headers' => $request->getHeaders(),
            'body' => (string)$request->getBody(),
        ];
    }

    private function getParsedResponse(ResponseInterface $response)
    {
        return [
            'status_code' => $response->getStatusCode(),
            'reason_phrase' => $response->getReasonPhrase(),
            'protocol_version' => $response->getProtocolVersion(),
            'headers' => $response->getHeaders(),
            'body' => (string)$response->getBody(),
        ];
    }
}
