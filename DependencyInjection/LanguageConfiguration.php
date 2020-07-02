<?php

namespace BastSys\LanguageBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class LanguageConfiguration implements ConfigurationInterface
{

    /**
     * Generates the configuration tree builder.
     *
     * @return TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder('language');
        $builder->getRootNode()
            ->children()
            ->arrayNode('locale')->isRequired()
            ->children()
            ->scalarNode('default')->isRequired()
            ->end()
            ->end()
            ->end();

        return $builder;
    }
}
