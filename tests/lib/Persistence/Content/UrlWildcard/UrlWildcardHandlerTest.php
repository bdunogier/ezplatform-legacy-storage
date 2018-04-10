<?php

/**
 * File contains: EzSystems\Tests\EzPlatformLegacyStorageEngine\Persistence\Content\UrlWildcardHandlerTest class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\Tests\EzPlatformLegacyStorageEngine\Persistence\Content\UrlWildcard;

use EzSystems\Tests\EzPlatformLegacyStorageEngine\Persistence\TestCase;
use EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\UrlWildcard\Handler;
use EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\UrlWildcard\Gateway\DoctrineDatabase;
use EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\UrlWildcard\Mapper;
use eZ\Publish\SPI\Persistence\Content\UrlWildcard;

/**
 * Test case for UrlWildcard Handler.
 */
class UrlWildcardHandlerTest extends TestCase
{
    /**
     * Test for the __construct() method.
     *
     * @covers \EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\UrlWildcard\Handler::__construct
     */
    public function testConstructor()
    {
        $handler = $this->getHandler();

        self::assertAttributeSame(
            $this->gateway,
            'gateway',
            $handler
        );
        self::assertAttributeSame(
            $this->mapper,
            'mapper',
            $handler
        );
    }

    /**
     * Test for the load() method.
     *
     * @covers \EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\UrlWildcard\Handler::load
     */
    public function testLoad()
    {
        $this->insertDatabaseFixture(__DIR__ . '/Gateway/_fixtures/urlwildcards.php');
        $handler = $this->getHandler();

        $urlWildcard = $handler->load(1);

        self::assertEquals(
            new UrlWildcard(
                array(
                    'id' => 1,
                    'sourceUrl' => '/developer/*',
                    'destinationUrl' => '/dev/{1}',
                    'forward' => false,
                )
            ),
            $urlWildcard
        );
    }

    /**
     * Test for the load() method.
     *
     * @covers \EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\UrlWildcard\Handler::load
     * @expectedException \eZ\Publish\API\Repository\Exceptions\NotFoundException
     */
    public function testLoadThrowsNotFoundException()
    {
        $this->insertDatabaseFixture(__DIR__ . '/Gateway/_fixtures/urlwildcards.php');
        $handler = $this->getHandler();

        $handler->load(100);
    }

    /**
     * Test for the create() method.
     *
     * @covers \EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\UrlWildcard\Handler::create
     * @depends testLoad
     */
    public function testCreate()
    {
        $this->insertDatabaseFixture(__DIR__ . '/Gateway/_fixtures/urlwildcards.php');
        $handler = $this->getHandler();

        $urlWildcard = $handler->create(
            'amber',
            'pattern',
            true
        );

        self::assertEquals(
            new UrlWildcard(
                array(
                    'id' => 4,
                    'sourceUrl' => '/amber',
                    'destinationUrl' => '/pattern',
                    'forward' => true,
                )
            ),
            $urlWildcard
        );

        self::assertEquals(
            $urlWildcard,
            $handler->load(4)
        );
    }

    /**
     * Test for the remove() method.
     *
     * @covers \EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\UrlWildcard\Handler::remove
     * @expectedException \eZ\Publish\API\Repository\Exceptions\NotFoundException
     * @depends testLoad
     */
    public function testRemove()
    {
        $this->insertDatabaseFixture(__DIR__ . '/Gateway/_fixtures/urlwildcards.php');
        $handler = $this->getHandler();

        $handler->remove(1);
        $handler->load(1);
    }

    /**
     * Test for the loadAll() method.
     *
     * @covers \EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\UrlWildcard\Handler::loadAll
     */
    public function testLoadAll()
    {
        $this->insertDatabaseFixture(__DIR__ . '/Gateway/_fixtures/urlwildcards.php');
        $handler = $this->getHandler();

        $urlWildcards = $handler->loadAll();

        self::assertEquals(
            array(
                new UrlWildcard($this->fixtureData[0]),
                new UrlWildcard($this->fixtureData[1]),
                new UrlWildcard($this->fixtureData[2]),
            ),
            $urlWildcards
        );
    }

    /**
     * Test for the loadAll() method.
     *
     * @covers \EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\UrlWildcard\Handler::loadAll
     */
    public function testLoadAllWithOffset()
    {
        $this->insertDatabaseFixture(__DIR__ . '/Gateway/_fixtures/urlwildcards.php');
        $handler = $this->getHandler();

        $urlWildcards = $handler->loadAll(2);

        self::assertEquals(
            array(
                new UrlWildcard($this->fixtureData[2]),
            ),
            $urlWildcards
        );
    }

    /**
     * Test for the loadAll() method.
     *
     * @covers \EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\UrlWildcard\Handler::loadAll
     */
    public function testLoadAllWithOffsetAndLimit()
    {
        $this->insertDatabaseFixture(__DIR__ . '/Gateway/_fixtures/urlwildcards.php');
        $handler = $this->getHandler();

        $urlWildcards = $handler->loadAll(1, 1);

        self::assertEquals(
            array(
                new UrlWildcard($this->fixtureData[1]),
            ),
            $urlWildcards
        );
    }

    protected $fixtureData = array(
        array(
            'id' => 1,
            'sourceUrl' => '/developer/*',
            'destinationUrl' => '/dev/{1}',
            'forward' => false,
        ),
        array(
            'id' => 2,
            'sourceUrl' => '/repository/*',
            'destinationUrl' => '/repo/{1}',
            'forward' => false,
        ),
        array(
            'id' => 3,
            'sourceUrl' => '/information/*',
            'destinationUrl' => '/info/{1}',
            'forward' => false,
        ),
    );

    /**
     * @var \EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\UrlWildcard\Gateway\DoctrineDatabase
     */
    protected $gateway;

    /**
     * @var \EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\UrlWildcard\Mapper
     */
    protected $mapper;

    /**
     * @var \EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\UrlWildcard\Handler
     */
    protected $urlWildcardHandler;

    /**
     * @return \EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\UrlWildcard\Handler
     */
    protected function getHandler()
    {
        if (!isset($this->urlWildcardHandler)) {
            $this->gateway = new DoctrineDatabase($this->getDatabaseHandler());
            $this->mapper = new Mapper();

            $this->urlWildcardHandler = new Handler(
                $this->gateway,
                $this->mapper
            );
        }

        return $this->urlWildcardHandler;
    }
}
