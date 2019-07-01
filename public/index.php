<?php

define(
    'APPLICATION_ENV',
    getenv('APPLICATION_ENV') ?  getenv('APPLICATION_ENV') : 'docker'
);
if (!defined('APPLICATION_PATH')) {
    define('APPLICATION_PATH', realpath(__DIR__ . '/../'));
}

require_once __DIR__ . '/../vendor/autoload.php';

use Antevenio\DddExample\Infrastructure\App;

$app = (new App())->getInstance();
$app->run();
