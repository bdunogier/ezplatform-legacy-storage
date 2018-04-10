<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformLegacyStorageEngine\Persistence\URL\Query\CriterionHandler;

use eZ\Publish\API\Repository\Values\URL\Query\Criterion;
use EzSystems\EzPlatformLegacyStorageEngine\Persistence\URL\Query\CriteriaConverter;
use EzSystems\EzPlatformLegacyStorageEngine\Persistence\URL\Query\CriterionHandler;
use eZ\Publish\Core\Persistence\Database\SelectQuery;
use PDO;

class VisibleOnly implements CriterionHandler
{
    /**
     * {@inheritdoc}
     */
    public function accept(Criterion $criterion)
    {
        return $criterion instanceof Criterion\VisibleOnly;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(CriteriaConverter $converter, SelectQuery $query, Criterion $criterion)
    {
        return $query->expr->in('ezurl.id', $this->getVisibleOnlySubQuery($query));
    }

    /**
     * Generate query that selects ids of visible URLs.
     *
     * @param \eZ\Publish\Core\Persistence\Database\SelectQuery $query
     * @return \eZ\Publish\Core\Persistence\Database\SelectQuery
     */
    protected function getVisibleOnlySubQuery(SelectQuery $query)
    {
        // TODO: The following query requires optimization
        $subSelect = $query->subSelect();
        $subSelect
            ->selectDistinct('ezurl_object_link.url_id')
            ->from('ezurl_object_link')
            ->innerJoin(
                'ezcontentobject_attribute',
                $query->expr->lAnd(
                    $query->expr->eq('ezurl_object_link.contentobject_attribute_id', 'ezcontentobject_attribute.id'),
                    $query->expr->eq('ezurl_object_link.contentobject_attribute_version', 'ezcontentobject_attribute.version')
                )
            )
            ->innerJoin(
                'ezcontentobject_tree',
                $query->expr->lAnd(
                    $query->expr->eq('ezcontentobject_tree.contentobject_id', 'ezcontentobject_attribute.contentobject_id'),
                    $query->expr->eq('ezcontentobject_tree.contentobject_version', 'ezcontentobject_attribute.version')
                )
            )
            ->where(
                $query->expr->eq(
                    'ezcontentobject_tree.is_invisible',
                    $query->bindValue(0, null, PDO::PARAM_INT)
                )
            );

        return $subSelect;
    }
}
