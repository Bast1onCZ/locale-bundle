<?php

namespace BastSys\LocaleBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Class LanguageExtension
 * @package BastSys\LocaleBundle\DependencyInjection
 * @author mirkl
 */
class LocaleExtension extends Extension
{
    /**
     * Loads a specific configuration.
     *
     * @param array $configs
     * @param ContainerBuilder $container
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        // load configuration
        $config = $this->processConfiguration(new LocaleConfiguration(), $configs);
        $container->setParameter('bastsys.language_bundle.locale.default', $config['locale']['default']);

        // load services
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('eventListener.yaml');
        $loader->load('repository.yaml');
        $loader->load('service.yaml');
    }
}
