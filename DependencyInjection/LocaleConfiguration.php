<?php

namespace BastSys\LanguageBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class LocaleConfiguration implements ConfigurationInterface
{

    /**
     * Generates the configuration tree builder.
     *
     * @return TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder('language');

        // @formatter:off
        $builder->getRootNode()
            ->children()
                ->arrayNode('locale')->isRequired()
                    ->children()
                        ->scalarNode('default')->isRequired()
                    ->end()
                ->end()
            ->end();
        // @formatter:on

        return $builder;
    }
}
