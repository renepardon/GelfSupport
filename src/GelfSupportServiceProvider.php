<?php

namespace RenePardon\GelfSupport;

use Gelf\Publisher;
use Gelf\Transport\UdpTransport;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\ServiceProvider;

/**
 * Class GelfSupportServiceProvider
 *
 * @package renepardon\GelfSupport
 */
class GelfSupportServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/gelfsupport.php' => config_path('gelfsupport.php'),
        ], 'config');
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $config = $this->app->make(Repository::class);

        $this->app->alias(ExceptionHandler::class, 'exceptions');
        $this->app->singleton('exceptions.repository', ExceptionHandlerRepository::class);
        $this->app->extend(ExceptionHandler::class, function ($handler, $app) use ($config) {
            return new Decorator($handler, $app['exceptions.repository'], $config);
        });

        $this->app->singleton(Graylog::class, function ($app) use ($config) {
            $transport = new UdpTransport(
                $config->get('gelfsupport.host'),
                $config->get('gelfsupport.port'),
                UdpTransport::CHUNK_SIZE_LAN
            );

            // While the UDP transport is itself a publisher, we wrap it in a real Publisher for convenience.
            // A publisher allows for message validation before transmission, and also supports sending
            // messages to multiple backends at once.
            $publisher = new Publisher();
            $publisher->addTransport($transport);

            return new Graylog($publisher, 'gelf-php');
        });
    }
}