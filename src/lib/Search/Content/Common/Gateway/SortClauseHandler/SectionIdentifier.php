<?php

/**
 * File containing a DoctrineDatabase sort clause handler class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformLegacyStorageEngine\Search\Content\Common\Gateway\SortClauseHandler;

use EzSystems\EzPlatformLegacyStorageEngine\Search\Content\Common\Gateway\SortClauseHandler;
use eZ\Publish\API\Repository\Values\Content\Query\SortClause;
use EzSystems\EzPlatformLegacyStorageEngine\Database\SelectQuery;

/**
 * Content locator gateway implementation using the DoctrineDatabase.
 */
class SectionIdentifier extends SortClauseHandler
{
    /**
     * Check if this sort clause handler accepts to handle the given sort clause.
     *
     * @param \eZ\Publish\API\Repository\Values\Content\Query\SortClause $sortClause
     *
     * @return bool
     */
    public function accept(SortClause $sortClause)
    {
        return $sortClause instanceof SortClause\SectionIdentifier;
    }

    /**
     * Apply selects to the query.
     *
     * Returns the name of the (aliased) column, which information should be
     * used for sorting.
     *
     * @param \EzSystems\EzPlatformLegacyStorageEngine\Database\SelectQuery $query
     * @param \eZ\Publish\API\Repository\Values\Content\Query\SortClause $sortClause
     * @param int $number
     *
     * @return string
     */
    public function applySelect(SelectQuery $query, SortClause $sortClause, $number)
    {
        $query
            ->select(
                $query->alias(
                    $this->dbHandler->quoteColumn(
                        'section_id',
                        'ezcontentobject'
                    ),
                    $column = $this->getSortColumnName($number)
                )
            );

        return $column;
    }
}
