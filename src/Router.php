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
use CrazyGoat\Router\Exceptions\MethodNotAllowed as CrazyRouterMethodNotAllowed;
use CrazyGoat\Router\Exceptions\RouteNotFound as CrazyRouterRouteNotFound;
use CrazyGoat\Router\Interfaces\Dispatcher;
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
        try {
            $routeInfo = $this->dispatcher->dispatch(
                $request->getMethod(), $request->getUri()->getPath()
            );

            return new Route(
                $routeInfo->getHandler(),
                null,
                $routeInfo->getVariables(),
                $routeInfo->getMiddlewareStack()
            );
        } catch (CrazyRouterRouteNotFound $exception) {
            throw new RouteNotFound('Route not found');
        } catch (CrazyRouterMethodNotAllowed $exception) {
            throw new RouteNotFound('Route not found');
        }
    }
}