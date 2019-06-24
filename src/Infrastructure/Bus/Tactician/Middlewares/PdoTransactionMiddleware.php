<?php


namespace Antevenio\DddExample\Infrastructure\Bus\Tactician\Middlewares;

use Antevenio\DddExample\Infrastructure\Repository\PdoWrapper;
use League\Tactician\Middleware;

class PdoTransactionMiddleware implements Middleware
{
    /**
     * @var PdoWrapper
     */
    private $pdo;

    public function __construct(PdoWrapper $pdo)
    {
        $this->pdo = $pdo;
    }

    public function execute($command, callable $next)
    {
        $nextOperation = function () use ($next, $command) {
            return $next($command);
        };

        $this->pdo->connect();
        $returnValue = $this->pdo->executeAtomically($nextOperation);
        $this->pdo->disconnect();

        return $returnValue;
    }
}
