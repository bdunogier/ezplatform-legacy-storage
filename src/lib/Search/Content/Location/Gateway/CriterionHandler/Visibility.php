<?php

/**
 * File containing the DoctrineDatabase location visibility criterion handler class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformLegacyStorageEngine\Search\Content\Location\Gateway\CriterionHandler;

use EzSystems\EzPlatformLegacyStorageEngine\Search\Content\Common\Gateway\CriterionHandler;
use EzSystems\EzPlatformLegacyStorageEngine\Search\Content\Common\Gateway\CriteriaConverter;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion;
use RuntimeException;
use EzSystems\EzPlatformLegacyStorageEngine\Database\SelectQuery;
use PDO;

/**
 * Location visibility criterion handler.
 */
class Visibility extends CriterionHandler
{
    /**
     * Check if this criterion handler accepts to handle the given criterion.
     *
     * @param \eZ\Publish\API\Repository\Values\Content\Query\Criterion $criterion
     *
     * @return bool
     */
    public function accept(Criterion $criterion)
    {
        return $criterion instanceof Criterion\Visibility;
    }

    /**
     * Generate query expression for a Criterion this handler accepts.
     *
     * accept() must be called before calling this method.
     *
     * @param \EzSystems\EzPlatformLegacyStorageEngine\Search\Content\Common\Gateway\CriteriaConverter $converter
     * @param \EzSystems\EzPlatformLegacyStorageEngine\Database\SelectQuery $query
     * @param \eZ\Publish\API\Repository\Values\Content\Query\Criterion $criterion
     * @param array $languageSettings
     *
     * @return \EzSystems\EzPlatformLegacyStorageEngine\Database\Expression
     */
    public function handle(
        CriteriaConverter $converter,
        SelectQuery $query,
        Criterion $criterion,
        array $languageSettings
    ) {
        $column = $this->dbHandler->quoteColumn('is_invisible', 'ezcontentobject_tree');

        switch ($criterion->value[0]) {
            case Criterion\Visibility::VISIBLE:
                return $query->expr->eq(
                    $column,
                    $query->bindValue(0, null, PDO::PARAM_INT)
                );

            case Criterion\Visibility::HIDDEN:
                return $query->expr->eq(
                    $column,
                    $query->bindValue(1, null, PDO::PARAM_INT)
                );

            default:
                throw new RuntimeException(
                    "Unknown value '{$criterion->value[0]}' for Visibility criterion handler."
                );
        }
    }
}
