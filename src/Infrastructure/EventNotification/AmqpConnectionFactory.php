<?php


namespace Antevenio\DddExample\Infrastructure\EventNotification;

use PhpAmqpLib\Connection\AbstractConnection;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class AmqpConnectionFactory
{
    public function __invoke($config) : AbstractConnection
    {
        return new AMQPStreamConnection(
            $config['host'],
            $config['port'],
            $config['user'],
            $config['password'],
            $config['vhost'],
            $config['insist'],
            $config['login_method'],
            $config['login_response'],
            $config['locale'],
            $config['connection_timeout'],
            $config['read_write_timeout'],
            $config['context'],
            $config['keepalive'],
            $config['heartbeat']
        );
    }
}
