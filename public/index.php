<?php

declare(strict_types=1);

/**
 * @var $container ContainerInterface
 */

use App\Middleware\CorsMiddleware;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Slim\App;
use Slim\Interfaces\CallableResolverInterface;
use Slim\Interfaces\RouteCollectorInterface;
use Slim\Interfaces\RouteResolverInterface;
use Slim\Middleware\BodyParsingMiddleware;
use Slim\Middleware\ErrorMiddleware;
use Slim\Middleware\RoutingMiddleware;
use Slim\Psr7\Factory\ServerRequestFactory;

$container = require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'bootstrap.php';
$request = ServerRequestFactory::createFromGlobals();

\Slim\Factory\AppFactory::create();

$app = new App(
    $container->get(ResponseFactoryInterface::class),
    $container,
    $container->get(CallableResolverInterface::class),
    $container->get(RouteCollectorInterface::class),
    $container->get(RouteResolverInterface::class)
);

$app->add($container->get(BodyParsingMiddleware::class));
$app->add($container->get(CorsMiddleware::class));
$app->add($container->get(RoutingMiddleware::class));
$app->add($container->get(ErrorMiddleware::class));
$app->run($request);