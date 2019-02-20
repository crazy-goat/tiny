<?php
declare(strict_types=1);

namespace CrazyGoat\Tiny;

use CrazyGoat\Router\DispatcherFactory;
use CrazyGoat\Router\RouteCollector;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use Nyholm\Psr7Server\ServerRequestCreator;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class App extends \CrazyGoat\Core\App
{
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        $this->initDefaults();
    }

    protected function initDefaults(): void
    {
        $this->initResponseRenderer();
        $this->initErrorHandler();
        $this->initRequestFactory();
        $this->initResponseFactory();
        $this->initRouter();
    }

    protected function initRequestFactory(): void
    {
        if (!$this->container->has('requestFactory')) {
            $this->requestFactory = function (): ServerRequestInterface {
                $psr17Factory = new Psr17Factory();

                $creator = new ServerRequestCreator(
                    $psr17Factory,
                    $psr17Factory,
                    $psr17Factory,
                    $psr17Factory
                );

                return $creator->fromGlobals();
            };
        }
    }

    protected function initResponseFactory(): void
    {
        if (!$this->container->has('responseFactory')) {
            $this->responseFactory = function (): ResponseInterface {
                return new Response();
            };
        }
    }

    private function initRouter(): void
    {
        if (!$this->container->has('router')) {
            $dispatcher = DispatcherFactory::createFromClosure(
                function (RouteCollector $r): void {
                    return;
                }
            );
            $this->router = new Router($dispatcher);
        }
    }

    private function initErrorHandler(): void
    {
        if (!$this->container->has('errorHandler')) {
            $this->errorHandler = new SimpleErrorHandler();
        }
    }

    private function initResponseRenderer(): void
    {
        if (!$this->container->has('responseRenderer')) {
            $this->renderer = new SimpleRenderer();
        }
    }
}