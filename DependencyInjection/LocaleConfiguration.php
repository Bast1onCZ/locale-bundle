<?php

namespace BastSys\LanguageBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class LocaleConfiguration
 * @package BastSys\LanguageBundle\DependencyInjection
 * @author mirkl
 */
class LocaleConfiguration implements ConfigurationInterface
{

    /**
     * Generates the configuration tree builder.
     *
     * @return TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder('locale');

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
