<?php

namespace RenePardon\GelfSupport\GelfBundle\DependencyInjection;

use RenePardon\GelfSupport\GelfBundle\MonologHandler;
use Symfony\Bundle\MonologBundle\DependencyInjection\MonologExtension as BaseMonologExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class GelfExtension
 *
 * This is the class that loads and manages your bundle configuration.
 *
 * @see     {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 * @package RenePardon\GelfSupport\GelfBundle\DependencyInjection
 */
class GelfExtension extends BaseMonologExtension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        // First load the parent Monolog stuff
        parent::load($configs, $container);

        // Now configure our custom overwrites
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('graylog.enabled', (bool)$config['graylog']['enabled']);
        $container->setParameter('graylog.host', (string)$config['graylog']['host']);
        $container->setParameter('graylog.port', (int)$config['graylog']['port']);
    }
}
