<?php

/**
 * File containing the ValueConverter class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformLegacyStorageEngine\Search\Content\Common\Gateway\CriterionHandler\FieldValue;

use eZ\Publish\API\Repository\Values\Content\Query\Criterion;
use EzSystems\EzPlatformLegacyStorageEngine\Database\SelectQuery;
use RuntimeException;

/**
 * Content locator gateway implementation using the DoctrineDatabase.
 */
class Converter
{
    /**
     * Criterion field value handler registry.
     *
     * @var \EzSystems\EzPlatformLegacyStorageEngine\Search\Content\Common\Gateway\CriterionHandler\FieldValue\HandlerRegistry
     */
    protected $registry;

    /**
     * Default Criterion field value handler.
     *
     * @var \EzSystems\EzPlatformLegacyStorageEngine\Search\Content\Common\Gateway\CriterionHandler\FieldValue\Handler
     */
    protected $defaultHandler;

    /**
     * Construct from an array of Criterion field value handlers.
     *
     * @param \EzSystems\EzPlatformLegacyStorageEngine\Search\Content\Common\Gateway\CriterionHandler\FieldValue\HandlerRegistry $registry
     * @param null|\EzSystems\EzPlatformLegacyStorageEngine\Search\Content\Common\Gateway\CriterionHandler\FieldValue\Handler $defaultHandler
     */
    public function __construct(HandlerRegistry $registry, Handler $defaultHandler = null)
    {
        $this->registry = $registry;
        $this->defaultHandler = $defaultHandler;
    }

    /**
     * Converts the criteria into query fragments.
     *
     * @throws \RuntimeException if Criterion is not applicable to its target
     *
     * @param string $fieldTypeIdentifier
     * @param \EzSystems\EzPlatformLegacyStorageEngine\Database\SelectQuery $query
     * @param \eZ\Publish\API\Repository\Values\Content\Query\Criterion $criterion
     * @param string $column
     *
     * @return \EzSystems\EzPlatformLegacyStorageEngine\Database\Expression
     */
    public function convertCriteria($fieldTypeIdentifier, SelectQuery $query, Criterion $criterion, $column)
    {
        if ($this->registry->has($fieldTypeIdentifier)) {
            return $this->registry->get($fieldTypeIdentifier)->handle($query, $criterion, $column);
        }

        if ($this->defaultHandler === null) {
            throw new RuntimeException("No conversion for a field type '$fieldTypeIdentifier' found.");
        }

        return $this->defaultHandler->handle($query, $criterion, $column);
    }
}
