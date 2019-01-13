<?php
declare(strict_types=1);

use CrazyGoat\Tiny\App;
use CrazyGoat\Tiny\Router;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;
use Pimple\Container;

error_reporting(E_ALL);
ini_set('display_errors', 'on');

include "../vendor/autoload.php";

$container = new Container();
$container['router'] = function () {
    $dispatcher = simpleDispatcher(function(RouteCollector $r): void {
        $r->addRoute('GET', '/', 'controller::main');
    });
    return new Router($dispatcher);
};

$container['controller::main'] = function () {
    return function ($request, \Psr\Http\Message\ResponseInterface $response) {
        $response->getBody()->write('<h1>Hello world!</h1>');
        return $response;
    };
};

$app = new App(
    new \Pimple\Psr11\Container($container)
);

$app->run();
