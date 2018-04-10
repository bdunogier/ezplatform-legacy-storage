<?php

/**
 * File containing the DoctrineDatabase Type Update Handler class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\Type\Update\Handler;

use EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\Type\Update\Handler;
use EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\Type\Gateway;
use EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\Type\ContentUpdater;
use eZ\Publish\SPI\Persistence\Content\Type;

/**
 * Doctrine database based type update handler.
 */
class DoctrineDatabase extends Handler
{
    /**
     * @var \EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\Type\Gateway
     */
    protected $contentTypeGateway;

    /**
     * Content updater.
     *
     * @var \EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\Type\ContentUpdater
     */
    protected $contentUpdater;

    /**
     * Creates a new content type update handler.
     *
     * @param \EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\Type\Gateway $contentTypeGateway
     * @param \EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\Type\ContentUpdater $contentUpdater
     */
    public function __construct(Gateway $contentTypeGateway, ContentUpdater $contentUpdater)
    {
        $this->contentTypeGateway = $contentTypeGateway;
        $this->contentUpdater = $contentUpdater;
    }

    /**
     * Updates existing content objects from $fromType to $toType.
     *
     * @param \eZ\Publish\SPI\Persistence\Content\Type $fromType
     * @param \eZ\Publish\SPI\Persistence\Content\Type $toType
     */
    public function updateContentObjects(Type $fromType, Type $toType)
    {
        $this->contentUpdater->applyUpdates(
            $fromType->id,
            $this->contentUpdater->determineActions($fromType, $toType)
        );
    }

    /**
     * Deletes $fromType and all of its field definitions.
     *
     * @param \eZ\Publish\SPI\Persistence\Content\Type $fromType
     */
    public function deleteOldType(Type $fromType)
    {
        $this->contentTypeGateway->delete($fromType->id, $fromType->status);
    }

    /**
     * Publishes $toType to $newStatus.
     *
     * @param \eZ\Publish\SPI\Persistence\Content\Type $toType
     * @param int $newStatus
     */
    public function publishNewType(Type $toType, $newStatus)
    {
        $this->contentTypeGateway->publishTypeAndFields(
            $toType->id,
            $toType->status,
            $newStatus
        );
    }
}
