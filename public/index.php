<?php

use Micro\Kernel;
use Symfony\Component\ErrorHandler\Debug;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

if (file_exists($envPath = dirname(__DIR__) . '/.env')) {
    (new Dotenv())->bootEnv($envPath);
}
error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);

$env = $_SERVER['APP_ENV'] ?? 'prod';
$debug = $_SERVER['APP_DEBUG'] ?? ('prod' !== $env);

if ($debug) {
    umask(0000);
    Debug::enable();
}

$kernel = new Kernel((string)$env, (bool)$debug);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);