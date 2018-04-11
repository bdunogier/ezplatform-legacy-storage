<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformLegacyStorageEngineBundle;

use EzSystems\EzPlatformLegacyStorageEngineBundle\DependencyInjection\Compiler;
use EzSystems\EzPlatformLegacyStorageEngineBundle\DependencyInjection\EzPlatformLegacyStorageExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * eZ Platform RichText FieldType Bundle.
 */
class EzPlatformLegacyStorageEngineBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        
        $container->addCompilerPass(new Compiler\Storage\FieldValueConverterRegistryPass());
        $container->addCompilerPass(new Compiler\Storage\RoleLimitationConverterPass());

        $container->addCompilerPass(new Compiler\Search\CriteriaConverterPass());
        $container->addCompilerPass(new Compiler\Search\CriterionFieldValueHandlerRegistryPass());
        $container->addCompilerPass(new Compiler\Search\SortClauseConverterPass());
    }

    public function getContainerExtension()
    {
        if (!isset($this->extension)) {
            $this->extension = new EzPlatformLegacyStorageExtension();
        }

        return $this->extension;
    }
}
