<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformLegacyStorageEngine\Persistence\URL\Query\CriterionHandler;

use eZ\Publish\API\Repository\Values\URL\Query\Criterion;
use EzSystems\EzPlatformLegacyStorageEngine\Persistence\URL\Query\CriteriaConverter;
use EzSystems\EzPlatformLegacyStorageEngine\Persistence\URL\Query\CriterionHandler;
use EzSystems\EzPlatformLegacyStorageEngine\Database\SelectQuery;

class Pattern implements CriterionHandler
{
    /**
     * {@inheritdoc}
     */
    public function accept(Criterion $criterion)
    {
        return $criterion instanceof Criterion\Pattern;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(CriteriaConverter $converter, SelectQuery $query, Criterion $criterion)
    {
        /** @var Criterion\Pattern $criterion */
        return $query->expr->like(
            'url',
            $query->bindValue('%' . $criterion->pattern . '%')
        );
    }
}
