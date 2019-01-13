<?php
declare(strict_types=1);

namespace CrazyGoat\Tiny;

use CrazyGoat\Core\Interfaces\RouteInterface;

class Route implements RouteInterface
{
    /**
     * @var string
     */
    private $handler;

    /**
     * @var ?string
     */
    private $name;


    /**
     * @var array
     */
    private $arguments;

    /**
     * @var array
     */
    private $middlewares;

    public function __construct(
        string $handler,
        ?string $name = null,
        array $arguments = [],
        array $middlewares = []
    ) {
        $this->name = $name;
        $this->handler = $handler;
        $this->arguments = $arguments;
        $this->middlewares = $middlewares;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getHandler(): string
    {
        return $this->handler;
    }

    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }

    public function getRouteArguments(): array
    {
        return $this->arguments;
    }
}