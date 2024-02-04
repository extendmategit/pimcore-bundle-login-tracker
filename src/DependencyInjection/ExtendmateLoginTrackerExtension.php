<?php
declare(strict_types=1);

/**
 *
 * @category   Pimcore
 * @package    Extendmate
 * @subpackage LoginTracker
 *
 * @author     Faiyaz Alam
 *
 * @link       https://github.com/faiyazalam
 */

namespace Extendmate\Pimcore\LoginTracker\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;


class ExtendmateLoginTrackerExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../../config'));
        $loader->load('services.yaml');

        $definition = $container->getDefinition('Extendmate\Pimcore\LoginTracker\Service\Configuration');
        $definition->replaceArgument('$config', $config);
    }
}
