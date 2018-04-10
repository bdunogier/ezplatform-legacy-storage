<?php

/**
 * File contains: EzSystems\Tests\EzPlatformLegacyStorageEngine\Persistence\TransactionHandlerTest class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\Tests\EzPlatformLegacyStorageEngine\Persistence;

use EzSystems\EzPlatformLegacyStorageEngine\Persistence\TransactionHandler;
use EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\Language\CachingHandler;
use EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\Type\MemoryCachingHandler;
use EzSystems\EzPlatformLegacyStorageEngine\Database\DatabaseHandler;
use Exception;

/**
 * Test case for TransactionHandler.
 */
class TransactionHandlerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Transaction handler to test.
     *
     * @var \EzSystems\EzPlatformLegacyStorageEngine\Persistence\TransactionHandler
     */
    protected $transactionHandler;

    /**
     * @var \EzSystems\EzPlatformLegacyStorageEngine\Database\DatabaseHandler|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $dbHandlerMock;

    /**
     * @var \eZ\Publish\SPI\Persistence\Content\Type\Handler|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $contentTypeHandlerMock;

    /**
     * @var \eZ\Publish\SPI\Persistence\Content\Language\Handler|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $languageHandlerMock;

    /**
     * @covers \EzSystems\EzPlatformLegacyStorageEngine\Persistence\TransactionHandler::__construct
     */
    public function testConstruct()
    {
        $handler = $this->getTransactionHandler();

        $this->assertAttributeSame(
            $this->getDatabaseHandlerMock(),
            'dbHandler',
            $handler
        );
        $this->assertAttributeSame(
            $this->getContentTypeHandlerMock(),
            'contentTypeHandler',
            $handler
        );
        $this->assertAttributeSame(
            $this->getLanguageHandlerMock(),
            'languageHandler',
            $handler
        );
    }

    /**
     * @covers \EzSystems\EzPlatformLegacyStorageEngine\Persistence\TransactionHandler::beginTransaction
     */
    public function testBeginTransaction()
    {
        $handler = $this->getTransactionHandler();
        $this->getDatabaseHandlerMock()
            ->expects($this->once())
            ->method('beginTransaction');
        $this->getContentTypeHandlerMock()
            ->expects($this->never())
            ->method($this->anything());
        $this->getLanguageHandlerMock()
            ->expects($this->never())
            ->method($this->anything());

        $handler->beginTransaction();
    }

    /**
     * @covers \EzSystems\EzPlatformLegacyStorageEngine\Persistence\TransactionHandler::commit
     */
    public function testCommit()
    {
        $handler = $this->getTransactionHandler();
        $this->getDatabaseHandlerMock()
            ->expects($this->once())
            ->method('commit');
        $this->getContentTypeHandlerMock()
            ->expects($this->never())
            ->method($this->anything());
        $this->getLanguageHandlerMock()
            ->expects($this->never())
            ->method($this->anything());

        $handler->commit();
    }

    /**
     * @covers \EzSystems\EzPlatformLegacyStorageEngine\Persistence\TransactionHandler::commit
     *
     * @expectedException \RuntimeException
     * @expectedExceptionMessage test
     */
    public function testCommitException()
    {
        $handler = $this->getTransactionHandler();
        $this->getDatabaseHandlerMock()
            ->expects($this->once())
            ->method('commit')
            ->will($this->throwException(new Exception('test')));
        $this->getContentTypeHandlerMock()
            ->expects($this->never())
            ->method($this->anything());
        $this->getLanguageHandlerMock()
            ->expects($this->never())
            ->method($this->anything());

        $handler->commit();
    }

    /**
     * @covers \EzSystems\EzPlatformLegacyStorageEngine\Persistence\TransactionHandler::rollback
     */
    public function testRollback()
    {
        $handler = $this->getTransactionHandler();
        $this->getDatabaseHandlerMock()
            ->expects($this->once())
            ->method('rollback');
        $this->getContentTypeHandlerMock()
            ->expects($this->once())
            ->method('clearCache');
        $this->getLanguageHandlerMock()
            ->expects($this->once())
            ->method('clearCache');

        $handler->rollback();
    }

    /**
     * @covers \EzSystems\EzPlatformLegacyStorageEngine\Persistence\TransactionHandler::rollback
     *
     * @expectedException \RuntimeException
     * @expectedExceptionMessage test
     */
    public function testRollbackException()
    {
        $handler = $this->getTransactionHandler();
        $this->getDatabaseHandlerMock()
            ->expects($this->once())
            ->method('rollback')
            ->will($this->throwException(new Exception('test')));
        $this->getContentTypeHandlerMock()
            ->expects($this->never())
            ->method($this->anything());
        $this->getLanguageHandlerMock()
            ->expects($this->never())
            ->method($this->anything());

        $handler->rollback();
    }

    /**
     * Returns a mock object for the Content Gateway.
     *
     * @return \EzSystems\EzPlatformLegacyStorageEngine\Persistence\TransactionHandler
     */
    protected function getTransactionHandler()
    {
        if (!isset($this->transactionHandler)) {
            $this->transactionHandler = new TransactionHandler(
                $this->getDatabaseHandlerMock(),
                $this->getContentTypeHandlerMock(),
                $this->getLanguageHandlerMock()
            );
        }

        return $this->transactionHandler;
    }

    /**
     * Returns a mock object for the Content Gateway.
     *
     * @return \EzSystems\EzPlatformLegacyStorageEngine\Database\DatabaseHandler|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getDatabaseHandlerMock()
    {
        if (!isset($this->dbHandlerMock)) {
            $this->dbHandlerMock = $this->getMockForAbstractClass(DatabaseHandler::class);
        }

        return $this->dbHandlerMock;
    }

    /**
     * Returns a mock object for the Content Type Handler.
     *
     * @return \EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\Type\MemoryCachingHandler|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getContentTypeHandlerMock()
    {
        if (!isset($this->contentTypeHandlerMock)) {
            $this->contentTypeHandlerMock = $this->createMock(MemoryCachingHandler::class);
        }

        return $this->contentTypeHandlerMock;
    }

    /**
     * Returns a mock object for the Content Language Gateway.
     *
     * @return \EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\Language\CachingHandler|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getLanguageHandlerMock()
    {
        if (!isset($this->languageHandlerMock)) {
            $this->languageHandlerMock = $this->createMock(CachingHandler::class);
        }

        return $this->languageHandlerMock;
    }
}
