<?php
declare(strict_types=1);

namespace CrazyGoat\Tiny;

use CrazyGoat\Core\Exceptions\RouteNotFound;
use CrazyGoat\Core\Interfaces\ErrorHandlerInterface;
use Psr\Http\Message\ResponseInterface;

final class SimpleErrorHandler implements ErrorHandlerInterface
{
    public function processError(\Exception $exception, ResponseInterface $response): ResponseInterface
    {
        if ($exception instanceof RouteNotFound) {
            return $this->processRouteNotFound($exception, $response);
        }
        return $this->processException($exception, $response);
    }

    private function processRouteNotFound(RouteNotFound $exception, ResponseInterface $response): ResponseInterface
    {
        $response = $response->withStatus(404);
        $response->getBody()->write('<h1>404 - Page not found.</h1>');
        $response->getBody()->rewind();
        return $response;
    }

    private function processException(\Exception $exception, ResponseInterface $response): ResponseInterface
    {
        $response = $response->withStatus(500);
        $response->getBody()->write(sprintf(
            '<h1>Fatal error "%s"<br/> in %s:%d</h1><br/>Stack trace:<br/><pre>%s</pre>',
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine(),
            htmlspecialchars($exception->getTraceAsString())
            )
        );
        $response->getBody()->rewind();
        return $response;
    }
}