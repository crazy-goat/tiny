<?php
declare(strict_types=1);

namespace CrazyGoat\Tiny;

use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;
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

    private function initRouter()
    {
        if (!$this->container->has('router')) {
            $dispatcher = simpleDispatcher(function(RouteCollector $r) {
                return;
            });
            $this->router = new Router($dispatcher);
        }
    }

    private function initErrorHandler()
    {
        if (!$this->container->has('errorHandler')) {
            $this->errorHandler = new SimpleErrorHandler();
        }
    }

    private function initResponseRenderer()
    {
        if (!$this->container->has('responseRenderer')) {
            $this->renderer = new SimpleRenderer();
        }
    }
}