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
    private $variables;

    /**
     * @var array
     */
    private $middlewareStack;

    public function __construct(
        string $handler,
        ?string $name = null,
        array $variables = [],
        array $middlewareStack = []
    ) {
        $this->name = $name;
        $this->handler = $handler;
        $this->variables = $variables;
        $this->middlewareStack = $middlewareStack;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getHandler(): string
    {
        return $this->handler;
    }

    public function getMiddlewareStack(): array
    {
        return $this->middlewareStack;
    }


    public function getVariables(): array
    {
        return $this->variables;
    }
}