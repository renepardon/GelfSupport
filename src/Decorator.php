<?php

namespace RenePardon\GelfSupport;

use Closure;
use Exception;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Psr\Log\LogLevel;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class Decorator
 *
 * @package RenePardon\GelfSupport
 */
class Decorator implements ExceptionHandler
{
    /**
     * @var ExceptionHandler $handler Laravel default exception handler.
     */
    protected $handler;

    /**
     * @var ExceptionHandlerRepository $handlers Custom exception handlers repository.
     */
    protected $handlers;

    /**
     * @var Repository
     */
    protected $config;

    /**
     * Set the dependencies.
     *
     * @param ExceptionHandler           $handler
     * @param ExceptionHandlerRepository $handlers
     */
    public function __construct(ExceptionHandler $handler, ExceptionHandlerRepository $handlers, Repository $config)
    {
        $this->handler = $handler;
        $this->handlers = $handlers;
        $this->config = $config;
    }

    /**
     * Report or log an exception.
     *
     * @param  \Exception $e
     *
     * @return void
     */
    public function report(Exception $e)
    {
        // Do the Graylog logging stuff if enabled
        if (true == $this->config->get('gelfsupport.enabled')) {
            $exceptionMessage = empty($e->getMessage()) ? 'no exception message set' : $e->getMessage();

            /** @var Graylog $graylog */
            $graylog = app(Graylog::class);
            $graylog->log(LogLevel::CRITICAL, $exceptionMessage, [
                'exception' => $e,
                'app_name'  => $this->config->get('app.name'),
            ]);
        }

        // Check for additional custom reporters/handlers
        foreach ($this->handlers->getReportersFor($e) as $reporter) {
            if ($report = $reporter($e)) {
                return $report;
            }
        }

        // Use the default report handler if no custom one was found
        $this->handler->report($e);
    }

    /**
     * Register a custom handler to report exceptions
     *
     * @param Closure $reporter
     *
     * @return int
     */
    public function reporter(Closure $reporter): int
    {
        return $this->handlers->addReporter($reporter);
    }

    /**
     * Render an exception into an HTTP response
     *
     * @param \Illuminate\Http\Request $request
     * @param Exception                $e
     *
     * @return Response
     */
    public function render($request, Exception $e): Response
    {
        foreach ($this->handlers->getRenderersFor($e) as $renderer) {
            if ($render = $renderer($e, $request)) {
                return $render;
            }
        }

        return $this->handler->render($request, $e);
    }

    /**
     * Register a custom handler to render exceptions
     *
     * @param Closure $renderer
     *
     * @return int
     */
    public function renderer(Closure $renderer): int
    {
        return $this->handlers->addRenderer($renderer);
    }

    /**
     * Render an exception to the console
     *
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param Exception                                         $e
     *
     * @return mixed
     */
    public function renderForConsole($output, Exception $e)
    {
        foreach ($this->handlers->getConsoleRenderersFor($e) as $renderer) {
            if ($render = $renderer($e, $output)) {
                return $render;
            }
        }

        $this->handler->renderForConsole($output, $e);
    }

    /**
     * Register a custom handler to render exceptions to the console
     *
     * @param Closure $renderer
     *
     * @return int
     */
    public function consoleRenderer(Closure $renderer): int
    {
        return $this->handlers->addConsoleRenderer($renderer);
    }
}
