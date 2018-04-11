<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformLegacyStorageEngineBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Config\FileLocator;

/**
 * eZ Platform RichText Field Type Bundle extension.
 */
class EzPlatformLegacyStorageExtension extends Extension
{
    public function getAlias()
    {
        return 'ezlegacystorage';
    }

    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../../lib/settings')
        );

        $loader->load('storage_engines/legacy.yml');
        $loader->load('search_engines/legacy.yml');
    }

    public function getConfiguration(array $config, ContainerBuilder $container)
    {
        return null;
    }
}
