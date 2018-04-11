<?php

/**
 * File containing the Legacy location criterion handler class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformLegacyStorageEngine\Search\Content\Common\Gateway;

use eZ\Publish\API\Repository\Values\Content\Query\Criterion;
use EzSystems\EzPlatformLegacyStorageEngine\Database\DatabaseHandler;
use EzSystems\EzPlatformLegacyStorageEngine\Database\SelectQuery;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\Operator;

abstract class CriterionHandler
{
    /**
     * Map of criterion operators to the respective function names in the zeta
     * Database abstraction layer.
     *
     * @var array
     */
    protected $comparatorMap = array(
        Operator::EQ => 'eq',
        Operator::GT => 'gt',
        Operator::GTE => 'gte',
        Operator::LT => 'lt',
        Operator::LTE => 'lte',
        Operator::LIKE => 'like',
    );

    /**
     * Database handler.
     *
     * @var \EzSystems\EzPlatformLegacyStorageEngine\Database\DatabaseHandler
     */
    protected $dbHandler;

    /**
     * Creates a new criterion handler.
     *
     * @param \EzSystems\EzPlatformLegacyStorageEngine\Database\DatabaseHandler $dbHandler
     */
    public function __construct(DatabaseHandler $dbHandler)
    {
        $this->dbHandler = $dbHandler;
    }

    /**
     * Check if this criterion handler accepts to handle the given criterion.
     *
     * @param \eZ\Publish\API\Repository\Values\Content\Query\Criterion $criterion
     *
     * @return bool
     */
    abstract public function accept(Criterion $criterion);

    /**
     * Generate query expression for a Criterion this handler accepts.
     *
     * accept() must be called before calling this method.
     *
     * @param \EzSystems\EzPlatformLegacyStorageEngine\Search\Content\Common\Gateway\CriteriaConverter $converter
     * @param \EzSystems\EzPlatformLegacyStorageEngine\Database\SelectQuery $query
     * @param \eZ\Publish\API\Repository\Values\Content\Query\Criterion $criterion
     * @param array $languageSettings
     */
    abstract public function handle(
        CriteriaConverter $converter,
        SelectQuery $query,
        Criterion $criterion,
        array $languageSettings
    );

    /**
     * Returns a unique table name.
     *
     * @return string
     */
    protected function getUniqueTableName()
    {
        return uniqid('CriterionHandler', true);
    }
}
