<?php

/**
 * File containing the Legacy Storage TransactionHandler.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformLegacyStorageEngine\Persistence;

use eZ\Publish\SPI\Persistence\TransactionHandler as TransactionHandlerInterface;
use EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\Type\MemoryCachingHandler as CachingContentTypeHandler;
use EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\Language\CachingHandler as CachingLanguageHandler;
use EzSystems\EzPlatformLegacyStorageEngine\Database\DatabaseHandler;
use Exception;
use RuntimeException;

/**
 * The Transaction handler for Legacy Storage Engine.
 *
 * @since 5.3
 */
class TransactionHandler implements TransactionHandlerInterface
{
    /**
     * @var \EzSystems\EzPlatformLegacyStorageEngine\Database\DatabaseHandler
     */
    protected $dbHandler;

    /**
     * @var \eZ\Publish\SPI\Persistence\Content\Type\Handler
     */
    protected $contentTypeHandler;

    /**
     * @var \eZ\Publish\SPI\Persistence\Content\Language\Handler
     */
    protected $languageHandler;

    /**
     * @param \EzSystems\EzPlatformLegacyStorageEngine\Database\DatabaseHandler $dbHandler
     * @param \EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\Type\MemoryCachingHandler $contentTypeHandler
     * @param \EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\Language\CachingHandler $languageHandler
     */
    public function __construct(
        DatabaseHandler $dbHandler,
        CachingContentTypeHandler $contentTypeHandler = null,
        CachingLanguageHandler $languageHandler = null
    ) {
        $this->dbHandler = $dbHandler;
        $this->contentTypeHandler = $contentTypeHandler;
        $this->languageHandler = $languageHandler;
    }

    /**
     * Begin transaction.
     */
    public function beginTransaction()
    {
        $this->dbHandler->beginTransaction();
    }

    /**
     * Commit transaction.
     *
     * Commit transaction, or throw exceptions if no transactions has been started.
     *
     * @throws \RuntimeException If no transaction has been started
     */
    public function commit()
    {
        try {
            $this->dbHandler->commit();
        } catch (Exception $e) {
            throw new RuntimeException($e->getMessage(), 0, $e);
        }
    }

    /**
     * Rollback transaction.
     *
     * Rollback transaction, or throw exceptions if no transactions has been started.
     *
     * @throws \RuntimeException If no transaction has been started
     */
    public function rollback()
    {
        try {
            $this->dbHandler->rollback();

            // Clear all caches after rollback
            if ($this->contentTypeHandler instanceof CachingContentTypeHandler) {
                $this->contentTypeHandler->clearCache();
            }

            if ($this->languageHandler instanceof CachingLanguageHandler) {
                $this->languageHandler->clearCache();
            }
        } catch (Exception $e) {
            throw new RuntimeException($e->getMessage(), 0, $e);
        }
    }
}
