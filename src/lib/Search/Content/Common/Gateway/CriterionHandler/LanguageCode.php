<?php

/**
 * File containing the DoctrineDatabase language code criterion handler class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformLegacyStorageEngine\Search\Content\Common\Gateway\CriterionHandler;

use EzSystems\EzPlatformLegacyStorageEngine\Search\Content\Common\Gateway\CriterionHandler;
use EzSystems\EzPlatformLegacyStorageEngine\Search\Content\Common\Gateway\CriteriaConverter;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion;
use EzSystems\EzPlatformLegacyStorageEngine\Database\DatabaseHandler;
use EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\Language\MaskGenerator;
use EzSystems\EzPlatformLegacyStorageEngine\Database\SelectQuery;

/**
 * LanguageCode criterion handler.
 */
class LanguageCode extends CriterionHandler
{
    /**
     * @var \EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\Language\MaskGenerator
     */
    private $maskGenerator;

    /**
     * Construct from language mask generator.
     *
     * @param \EzSystems\EzPlatformLegacyStorageEngine\Database\DatabaseHandler $dbHandler
     * @param \EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\Language\MaskGenerator $maskGenerator
     */
    public function __construct(DatabaseHandler $dbHandler, MaskGenerator $maskGenerator)
    {
        $this->maskGenerator = $maskGenerator;
        parent::__construct($dbHandler);
    }

    /**
     * Check if this criterion handler accepts to handle the given criterion.
     *
     * @param \eZ\Publish\API\Repository\Values\Content\Query\Criterion $criterion
     *
     * @return bool
     */
    public function accept(Criterion $criterion)
    {
        return $criterion instanceof Criterion\LanguageCode;
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
        $languages = array_flip($criterion->value);
        /* @var $criterion \eZ\Publish\API\Repository\Values\Content\Query\Criterion\LanguageCode */
        $languages['always-available'] = $criterion->matchAlwaysAvailable;

        return $query->expr->gt(
            $query->expr->bitAnd(
                $this->dbHandler->quoteColumn('language_mask', 'ezcontentobject'),
                // @todo: Use a cached version of mask generator when implemented
                $this->maskGenerator->generateLanguageMask($languages)
            ),
            0
        );
    }
}
