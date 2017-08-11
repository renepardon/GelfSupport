<?php

namespace RenePardon\GelfSupport\GelfBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 *
 * This is the class that validates and merges configuration from your app/config files.
 *
 * @see     {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 * @package RenePardon\GelfSupport\GelfBundle\DependencyInjection
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('gelf');
        $rootNode->children()
            ->arrayNode('graylog')->children()
            ->booleanNode('enabled')->end()
            ->scalarNode('host')->end()
            ->integerNode('port')->end()
            ->end()->end()
            ->end();

        return $treeBuilder;
    }
}
