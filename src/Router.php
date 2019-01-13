<?php
/**
 * Created by PhpStorm.
 * User: piotr
 * Date: 13.01.19
 * Time: 17:35
 */

namespace CrazyGoat\Tiny;


use CrazyGoat\Core\Exceptions\RouteNotFound;
use CrazyGoat\Core\Interfaces\RouteInterface;
use CrazyGoat\Core\Interfaces\RouterInterface;
use CrazyGoat\Router\Dispatcher;
use Psr\Http\Message\ServerRequestInterface;

class Router implements RouterInterface
{
    /**
     * @var Dispatcher
     */
    private $dispatcher;

    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param ServerRequestInterface $request
     * @return RouteInterface
     * @throws RouteNotFound
     */
    public function dispatch(ServerRequestInterface $request): RouteInterface
    {
        $routeInfo = $this->dispatcher->dispatch(
            $request->getMethod(), $request->getUri()->getPath()
        );

        switch ($routeInfo[0]) {
            case Dispatcher::FOUND:
                return new Route(
                    $routeInfo[1], null, $routeInfo[2], $routeInfo[3]
                );
                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
            case Dispatcher::NOT_FOUND:
            default:
                throw new RouteNotFound('Route not found');
                break;
        }
    }
}