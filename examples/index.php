<?php
declare(strict_types=1);

use CrazyGoat\Tiny\{App, Router};
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Pimple\Container;

error_reporting(E_ALL);
ini_set('display_errors', 'on');

include "../vendor/autoload.php";

$container = new Container();
$container['router'] = function () {
    $dispatcher = simpleDispatcher(function(RouteCollector $r): void {
        $r->addRoute('GET', '/', 'controller::main');
        $r->addRoute('GET', '/hello[/{name}]', 'controller::hello');
    });
    return new Router($dispatcher);
};

$container['controller::main'] = function () {
    return function (ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {
        $response->getBody()->write('<h1>Hello world!</h1>');
        return $response;
    };
};

$container['controller::hello'] = function () {
    return function (ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {
        $response->getBody()->write('<h1>Hello '.$request->getAttribute('name', 'unknown').'!</h1>');
        return $response;
    };
};

$app = new App(
    new \Pimple\Psr11\Container($container)
);

$app->run();
