<?php

namespace Antevenio\DddExample\Infrastructure;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class CorsMiddleware
{

    public function __invoke(Request $request, Response $response, $next)
    {
        $response = $next($request, $response);
        return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Methods', implode(', ', ['GET', 'POST', 'OPTIONS']))
        ->withHeader(
            'Access-Control-Allow-Headers',
            'Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With'
        );
    }
}
