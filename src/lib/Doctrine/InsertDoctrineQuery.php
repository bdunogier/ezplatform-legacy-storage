<?php

/**
 * File containing an interface for the Doctrine database abstractions.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformLegacyStorageEngine\Doctrine;

use EzSystems\EzPlatformLegacyStorageEngine\Database\InsertQuery;
use EzSystems\EzPlatformLegacyStorageEngine\Database\QueryException;

/**
 * Class InsertDoctrineQuery.
 *
 * @deprecated Since 6.13, please use Doctrine DBAL instead (@ezpublish.persistence.connection)
 *             it provides richer and more powerful DB abstraction which is also easier to use.
 */
class InsertDoctrineQuery extends AbstractDoctrineQuery implements InsertQuery
{
    /**
     * @var string
     */
    private $table;

    /**
     * @var array
     */
    private $values = array();

    /**
     * Opens the query and sets the target table to $table.
     *
     * insertInto() returns a pointer to $this.
     *
     * @param string $table
     *
     * @return \EzSystems\EzPlatformLegacyStorageEngine\Database\InsertQuery
     */
    public function insertInto($table)
    {
        $this->table = $table;

        return $this;
    }

    /**
     * The insert query will set the column $column to the value $expression.
     *
     * set() returns a pointer to $this.
     *
     * @param string $column
     * @param string $expression
     *
     * @return \EzSystems\EzPlatformLegacyStorageEngine\Database\InsertQuery
     */
    public function set($column, $expression)
    {
        $this->values[$column] = $expression;

        return $this;
    }

    /**
     * @return string
     */
    public function getQuery()
    {
        if (strlen($this->table) === 0) {
            throw new QueryException('Missing table name');
        }

        if (count($this->values) === 0) {
            throw new QueryException('Missing values');
        }

        return 'INSERT INTO ' . $this->table
               . ' (' . implode(', ', array_keys($this->values)) . ')'
               . ' VALUES (' . implode(', ', $this->values) . ')';
    }
}
