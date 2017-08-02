<?php

namespace RenePardon\GelfSupport;

use Closure;
use Exception;
use ReflectionFunction;

/**
 * Class ExceptionHandlerRepository
 *
 * @package renepardon\GelfSupport
 */
class ExceptionHandlerRepository
{
    /**
     * List of handlers reporting exceptions
     *
     * @var array
     */
    protected $reporters = [];

    /**
     * List of handlers rendering exceptions
     *
     * @var array
     */
    protected $renderers = [];

    /**
     * List of handlers rendering exceptions to the console
     *
     * @var array
     */
    protected $consoleRenderers = [];

    /**
     * Register a custom handler to report exceptions
     *
     * @param Closure $reporter
     *
     * @return int
     */
    public function addReporter(Closure $reporter): int
    {
        return array_unshift($this->reporters, $reporter);
    }

    /**
     * Register a custom handler to render exceptions
     *
     * @param Closure $renderer
     *
     * @return int
     */
    public function addRenderer(Closure $renderer): int
    {
        return array_unshift($this->renderers, $renderer);
    }

    /**
     * Register a custom handler to render exceptions to the console
     *
     * @param Closure $renderer
     *
     * @return int
     */
    public function addConsoleRenderer(Closure $renderer): int
    {
        return array_unshift($this->consoleRenderers, $renderer);
    }

    /**
     * Retrieve all the reporters that handle the given exception
     *
     * @param Exception $e
     *
     * @return array
     */
    public function getReportersFor(Exception $e): array
    {
        return array_filter($this->reporters, $this->handlesException($e));
    }

    /**
     * Retrieve the filter to get only handlers that handle the given exception
     *
     * @param Exception $e
     *
     * @return Closure
     */
    protected function handlesException(Exception $e): Closure
    {
        return function (Closure $handler) use ($e) {
            $reflection = new ReflectionFunction($handler);

            if (!$params = $reflection->getParameters()) {
                return true;
            }

            return $params[0]->getClass() ? $params[0]->getClass()->isInstance($e) : true;
        };
    }

    /**
     * Retrieve all the renderers that handle the given exception
     *
     * @param Exception $e
     *
     * @return array
     */
    public function getRenderersFor(Exception $e): array
    {
        return array_filter($this->renderers, $this->handlesException($e));
    }

    /**
     * Retrieve all the renderers for console that handle the given exception
     *
     * @param Exception $e
     *
     * @return array
     */
    public function getConsoleRenderersFor(Exception $e): array
    {
        return array_filter($this->consoleRenderers, $this->handlesException($e));
    }
}
