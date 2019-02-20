<?php
declare(strict_types=1);

use CrazyGoat\Router\DispatcherFactory;
use CrazyGoat\Router\RouteCollector;
use CrazyGoat\Tiny\{App, Router};
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Pimple\Container;

include "../vendor/autoload.php";

$container = new Container();
$container['router'] = function () {
    $dispatcher = DispatcherFactory::createFromClosure(
        function (RouteCollector $r): void {
            $r->get('/', 'controller::main');
            $r->get('/hello[/{name}]', 'controller::hello');
        }
    );
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
        $response->getBody()->write('<h1>Hello ' . $request->getAttribute('name', 'unknown') . '!</h1>');
        return $response;
    };
};

$app = new App(
    new \Pimple\Psr11\Container($container)
);

$app->run();
