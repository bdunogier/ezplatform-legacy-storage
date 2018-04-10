<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformLegacyStorageEngine\Persistence\URL\Query;

use eZ\Publish\API\Repository\Values\URL\Query\Criterion;
use EzSystems\EzPlatformLegacyStorageEngine\Database\SelectQuery;

interface CriterionHandler
{
    /**
     * Check if this criterion handler accepts to handle the given criterion.
     *
     * @param \eZ\Publish\API\Repository\Values\URL\Query\Criterion $criterion
     * @return bool
     */
    public function accept(Criterion $criterion);

    /**
     * Generate query expression for a Criterion this handler accepts.
     *
     * accept() must be called before calling this method.
     *
     * @param \EzSystems\EzPlatformLegacyStorageEngine\Persistence\URL\Query\CriteriaConverter $converter
     * @param \EzSystems\EzPlatformLegacyStorageEngine\Database\SelectQuery $query
     * @param \eZ\Publish\API\Repository\Values\URL\Query\Criterion $criterion
     * @return \EzSystems\EzPlatformLegacyStorageEngine\Database\Expression|string
     */
    public function handle(CriteriaConverter $converter, SelectQuery $query, Criterion $criterion);
}
