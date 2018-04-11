<?php
/**
 * File containing the WordIndexer Gateway class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformLegacyStorageEngine\Search\Content\WordIndexer;

use eZ\Publish\SPI\Persistence\Content;
use EzSystems\EzPlatformLegacyStorageEngine\Search\Content\FullTextData;

/**
 * The WordIndexer Gateway abstracts indexing of content full text data.
 */
abstract class Gateway
{
    /**
     * Index search engine FullTextData objects corresponding to content object field values.
     *
     * @param \EzSystems\EzPlatformLegacyStorageEngine\Search\Content\FullTextData $fullTextValue
     */
    abstract public function index(FullTextData $fullTextValue);

    /**
     * Remove whole content or a specific version from index.
     *
     * @param mixed      $contentId
     * @param mixed|null $versionId
     */
    abstract public function remove($contentId, $versionId = null);

    /**
     * Indexes an array of FullTextData objects.
     *
     * @param \EzSystems\EzPlatformLegacyStorageEngine\Search\Content\FullTextData[] $fullTextBulkData
     */
    abstract public function bulkIndex(array $fullTextBulkData);

    /**
     * Remove entire search index.
     */
    abstract public function purgeIndex();
}
