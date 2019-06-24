<?php

namespace Antevenio\DddExample\Infrastructure\Ui\Http\Handlers;

use Crell\ApiProblem\ApiProblem;
use Crell\ApiProblem\HttpConverter;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use Antevenio\DddExample\Model\Exception;
use Antevenio\DddExample\Model\ValidationException;

use Antevenio\DddExample\Infrastructure\CorsMiddleware;

class ErrorApiProblemHandler
{
    const DEFAULT_TYPE = 'http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html';

    const HTTP_CODES = [
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing', // WebDAV; RFC 2518
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information', // since HTTP/1.1
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status', // WebDAV; RFC 4918
        208 => 'Already Reported', // WebDAV; RFC 5842
        226 => 'IM Used', // RFC 3229
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other', // since HTTP/1.1
        304 => 'Not Modified',
        305 => 'Use Proxy', // since HTTP/1.1
        306 => 'Switch Proxy',
        307 => 'Temporary Redirect', // since HTTP/1.1
        308 => 'Permanent Redirect', // approved as experimental RFC
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot', // RFC 2324
        419 => 'Authentication Timeout', // not in RFC 2616
        420 => 'Enhance Your Calm', // Twitter
        420 => 'Method Failure', // Spring Framework
        422 => 'Unprocessable Entity', // WebDAV; RFC 4918
        423 => 'Locked', // WebDAV; RFC 4918
        424 => 'Failed Dependency', // WebDAV; RFC 4918
        424 => 'Method Failure', // WebDAV)
        425 => 'Unordered Collection', // Internet draft
        426 => 'Upgrade Required', // RFC 2817
        428 => 'Precondition Required', // RFC 6585
        429 => 'Too Many Requests', // RFC 6585
        431 => 'Request Header Fields Too Large', // RFC 6585
        444 => 'No Response', // Nginx
        449 => 'Retry With', // Microsoft
        450 => 'Blocked by Windows Parental Controls', // Microsoft
        451 => 'Redirect', // Microsoft
        451 => 'Unavailable For Legal Reasons', // Internet draft
        494 => 'Request Header Too Large', // Nginx
        495 => 'Cert Error', // Nginx
        496 => 'No Cert', // Nginx
        497 => 'HTTP to HTTPS', // Nginx
        499 => 'Client Closed Request', // Nginx
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates', // RFC 2295
        507 => 'Insufficient Storage', // WebDAV; RFC 4918
        508 => 'Loop Detected', // WebDAV; RFC 5842
        509 => 'Bandwidth Limit Exceeded', // Apache bw/limited extension
        510 => 'Not Extended', // RFC 2774
        511 => 'Network Authentication Required', // RFC 6585
        598 => 'Network read timeout error', // Unknown
        599 => 'Network connect timeout error', // Unknown
    ];

    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    private $corsMiddleware;

    /**
     * ErrorApiProblemHandler constructor.
     * @param ResponseFactoryInterface $responseFactory
     * @param $corsMiddleware
     */
    public function __construct(ResponseFactoryInterface $responseFactory, $corsMiddleware = null)
    {
        $this->responseFactory = $responseFactory;
        $this->corsMiddleware = empty($corsMiddleware) ? new CorsMiddleware() : $corsMiddleware;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, \Exception $exception)
    {
        $status = $exception->getCode();
        $detail = $exception->getMessage();
        
        $problem = (new ApiProblem());
        
        if ($exception instanceof ValidationException) {
            $problem['validation_messages'] = $exception->getValidationMessages();
        }
        
        if ($exception instanceof Exception) {
            $problem['global_messages'] = (object)[
                $exception->getKey() => $exception->getValidation()
            ];
        }
        
        if (!array_key_exists($status, self::HTTP_CODES)) {
            $status = 500;
            $title = get_class($exception);
        } else {
            $title = self::HTTP_CODES[$status];
        }
        
        $problem->setTitle($title)
            ->setType(self::DEFAULT_TYPE)
            ->setDetail($detail)
            ->setStatus($status);
        
        $resp = (new HttpConverter($this->responseFactory, true))->toJsonResponse($problem);
        $respCors = ($this->corsMiddleware)($request, $resp, function ($req, $resp) {
            return $resp;
        });
        
        return $respCors;
    }
}
