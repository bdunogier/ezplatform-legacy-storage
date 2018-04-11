<?php

/**
 * File containing the DoctrineDatabase location main status criterion handler class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformLegacyStorageEngine\Search\Content\Location\Gateway\CriterionHandler\Location;

use EzSystems\EzPlatformLegacyStorageEngine\Search\Content\Common\Gateway\CriterionHandler;
use EzSystems\EzPlatformLegacyStorageEngine\Search\Content\Common\Gateway\CriteriaConverter;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion;
use RuntimeException;
use EzSystems\EzPlatformLegacyStorageEngine\Database\SelectQuery;

/**
 * Location main status criterion handler.
 */
class IsMainLocation extends CriterionHandler
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
        return $criterion instanceof Criterion\Location\IsMainLocation;
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
        $idColumn = $this->dbHandler->quoteColumn('node_id', 'ezcontentobject_tree');
        $mainIdColumn = $this->dbHandler->quoteColumn('main_node_id', 'ezcontentobject_tree');

        switch ($criterion->value[0]) {
            case Criterion\Location\IsMainLocation::MAIN:
                return $query->expr->eq(
                    $idColumn,
                    $mainIdColumn
                );

            case Criterion\Location\IsMainLocation::NOT_MAIN:
                return $query->expr->neq(
                    $idColumn,
                    $mainIdColumn
                );

            default:
                throw new RuntimeException(
                    "Unknown value '{$criterion->value[0]}' for IsMainLocation criterion handler."
                );
        }
    }
}
