<?php

/**
 * File containing the DoctrineDatabase content type group criterion handler class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformLegacyStorageEngine\Search\Content\Common\Gateway\CriterionHandler;

use EzSystems\EzPlatformLegacyStorageEngine\Search\Content\Common\Gateway\CriterionHandler;
use EzSystems\EzPlatformLegacyStorageEngine\Search\Content\Common\Gateway\CriteriaConverter;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion;
use EzSystems\EzPlatformLegacyStorageEngine\Database\SelectQuery;

/**
 * Content type group criterion handler.
 */
class ContentTypeGroupId extends CriterionHandler
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
        return $criterion instanceof Criterion\ContentTypeGroupId;
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
        $subSelect = $query->subSelect();
        $subSelect
            ->select(
                $this->dbHandler->quoteColumn('contentclass_id')
            )->from(
                $this->dbHandler->quoteTable('ezcontentclass_classgroup')
            )->where(
                $query->expr->in(
                    $this->dbHandler->quoteColumn('group_id'),
                    $criterion->value
                )
            );

        return $query->expr->in(
            $this->dbHandler->quoteColumn('contentclass_id', 'ezcontentobject'),
            $subSelect
        );
    }
}
