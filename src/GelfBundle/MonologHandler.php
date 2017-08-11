<?php

namespace RenePardon\GelfSupport\GelfBundle;

use Gelf\Message;
use Gelf\Publisher;
use Gelf\Transport\UdpTransport;
use Monolog\Handler\GelfHandler;
use Monolog\Logger;
use Symfony\Component\DependencyInjection\Container;

/**
 * Class MonologHandler
 *
 * @package RenePardon\GelfSupport\GelfBundle
 */
class MonologHandler extends GelfHandler
{
    /**
     * @var Container
     */
    protected $serviceContainer;

    /**
     * @param Container $serviceContainer
     */
    public function __construct(Container $serviceContainer)
    {
        $this->serviceContainer = $serviceContainer;

        $transport = new UdpTransport(
            $this->serviceContainer->getParameter('graylog.host'),
            $this->serviceContainer->getParameter('graylog.port'),
            // static::$host,
            // static::$port,
            UdpTransport::CHUNK_SIZE_LAN
        );

        $publisher = new Publisher();
        $publisher->addTransport($transport);

        parent::__construct($publisher, Logger::NOTICE, true);
    }

    /**
     * {@inheritdoc}
     */
    protected function write(array $record)
    {
        if ($this->serviceContainer->getParameter('graylog.enabled')) {

            $facility = 'gelf-php';
            $record['channel'] = $facility;

            /** @var Message $gelfMessage */
            $gelfMessage = $record['formatted'];
            $gelfMessage->setFacility($facility);
            $record['formatted'] = $gelfMessage;

            $this->publisher->publish($record['formatted']);
        }
    }
}
