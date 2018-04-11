<?php

/**
 * File containing the DoctrineDatabase sort clause handler class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformLegacyStorageEngine\Search\Content\Common\Gateway;

use EzSystems\EzPlatformLegacyStorageEngine\Database\DatabaseHandler;
use eZ\Publish\API\Repository\Values\Content\Query\SortClause;
use EzSystems\EzPlatformLegacyStorageEngine\Database\SelectQuery;

/**
 * Handler for a single sort clause.
 */
abstract class SortClauseHandler
{
    /**
     * Database handler.
     *
     * @var \EzSystems\EzPlatformLegacyStorageEngine\Database\DatabaseHandler
     */
    protected $dbHandler;

    /**
     * Creates a new sort clause handler.
     *
     * @param \EzSystems\EzPlatformLegacyStorageEngine\Database\DatabaseHandler $dbHandler
     */
    public function __construct(DatabaseHandler $dbHandler)
    {
        $this->dbHandler = $dbHandler;
    }

    /**
     * Check if this sort clause handler accepts to handle the given sort clause.
     *
     * @param \eZ\Publish\API\Repository\Values\Content\Query\SortClause $sortClause
     *
     * @return bool
     */
    abstract public function accept(SortClause $sortClause);

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
    abstract public function applySelect(SelectQuery $query, SortClause $sortClause, $number);

    /**
     * Applies joins to the query.
     *
     * @param \EzSystems\EzPlatformLegacyStorageEngine\Database\SelectQuery $query
     * @param \eZ\Publish\API\Repository\Values\Content\Query\SortClause $sortClause
     * @param int $number
     * @param array $languageSettings
     */
    public function applyJoin(
        SelectQuery $query,
        SortClause $sortClause,
        $number,
        array $languageSettings
    ) {
    }

    /**
     * Returns the quoted sort column name.
     *
     * @param int $number
     *
     * @return string
     */
    protected function getSortColumnName($number)
    {
        return $this->dbHandler->quoteIdentifier('sort_column_' . $number);
    }

    /**
     * Returns the sort table name.
     *
     * @param int $number
     * @param null|string $externalTableName
     *
     * @return string
     */
    protected function getSortTableName($number, $externalTableName = null)
    {
        return 'sort_table_' . ($externalTableName !== null ? $externalTableName . '_' : '') . $number;
    }
}
