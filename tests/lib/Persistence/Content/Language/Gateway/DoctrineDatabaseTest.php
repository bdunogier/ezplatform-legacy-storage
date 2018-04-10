<?php

/**
 * File contains: EzSystems\Tests\EzPlatformLegacyStorageEngine\Persistence\Content\Language\Gateway\DoctrineDatabaseTest class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\Tests\EzPlatformLegacyStorageEngine\Persistence\Content\Language\Gateway;

use EzSystems\Tests\EzPlatformLegacyStorageEngine\Persistence\TestCase;
use EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\Language\Gateway\DoctrineDatabase;
use eZ\Publish\SPI\Persistence\Content\Language;

/**
 * Test case for EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\Language\Gateway\DoctrineDatabase.
 */
class DoctrineDatabaseTest extends TestCase
{
    /**
     * Database gateway to test.
     *
     * @var \EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\Language\Gateway\DoctrineDatabase
     */
    protected $databaseGateway;

    /**
     * Inserts DB fixture.
     */
    public function setUp()
    {
        parent::setUp();

        $this->insertDatabaseFixture(
            __DIR__ . '/../../_fixtures/languages.php'
        );
    }

    /**
     * @covers \EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\Language\Gateway\DoctrineDatabase::__construct
     */
    public function testCtor()
    {
        $handler = $this->getDatabaseHandler();
        $gateway = $this->getDatabaseGateway();

        $this->assertAttributeSame(
            $handler,
            'dbHandler',
            $gateway
        );
    }

    /**
     * @covers \EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\Language\Gateway\DoctrineDatabase::insertLanguage
     * @covers \EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\Language\Gateway\DoctrineDatabase::setCommonLanguageColumns
     */
    public function testInsertLanguage()
    {
        $gateway = $this->getDatabaseGateway();

        $gateway->insertLanguage($this->getLanguageFixture());

        $this->assertQueryResult(
            array(
                array(
                    'id' => '8',
                    'locale' => 'de-DE',
                    'name' => 'Deutsch (Deutschland)',
                    'disabled' => '0',
                ),
            ),
            $this->getDatabaseHandler()->createSelectQuery()
                ->select('id', 'locale', 'name', 'disabled')
                ->from('ezcontent_language')
                ->where('id=8')
        );
    }

    /**
     * Returns a Language fixture.
     *
     * @return \eZ\Publish\SPI\Persistence\Content\Language
     */
    protected function getLanguageFixture()
    {
        $language = new Language();

        $language->languageCode = 'de-DE';
        $language->name = 'Deutsch (Deutschland)';
        $language->isEnabled = true;

        return $language;
    }

    /**
     * @covers \EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\Language\Gateway\DoctrineDatabase::updateLanguage
     * @covers \EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\Language\Gateway\DoctrineDatabase::setCommonLanguageColumns
     */
    public function testUpdateLanguage()
    {
        $gateway = $this->getDatabaseGateway();

        $language = $this->getLanguageFixture();
        $language->id = 2;

        $gateway->updateLanguage($language);

        $this->assertQueryResult(
            array(
                array(
                    'id' => '2',
                    'locale' => 'de-DE',
                    'name' => 'Deutsch (Deutschland)',
                    'disabled' => '0',
                ),
            ),
            $this->getDatabaseHandler()->createSelectQuery()
                ->select('id', 'locale', 'name', 'disabled')
                ->from('ezcontent_language')
                ->where('id=2')
        );
    }

    /**
     * @covers \EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\Language\Gateway\DoctrineDatabase::loadLanguageData
     * @covers \EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\Language\Gateway\DoctrineDatabase::createFindQuery
     */
    public function testLoadLanguageData()
    {
        $gateway = $this->getDatabaseGateway();

        $result = $gateway->loadLanguageData(2);

        $this->assertEquals(
            array(
                array(
                    'id' => '2',
                    'locale' => 'eng-US',
                    'name' => 'English (American)',
                    'disabled' => '0',
                ),
            ),
            $result
        );
    }

    /**
     * @covers \EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\Language\Gateway\DoctrineDatabase::loadAllLanguagesData
     * @covers \EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\Language\Gateway\DoctrineDatabase::createFindQuery
     */
    public function testLoadAllLanguagesData()
    {
        $gateway = $this->getDatabaseGateway();

        $result = $gateway->loadAllLanguagesData();

        $this->assertEquals(
            array(
                array(
                    'id' => '2',
                    'locale' => 'eng-US',
                    'name' => 'English (American)',
                    'disabled' => '0',
                ),
                array(
                    'id' => '4',
                    'locale' => 'eng-GB',
                    'name' => 'English (United Kingdom)',
                    'disabled' => '0',
                ),
            ),
            $result
        );
    }

    /**
     * @covers \EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\Language\Gateway\DoctrineDatabase::deleteLanguage
     */
    public function testDeleteLanguage()
    {
        $gateway = $this->getDatabaseGateway();

        $result = $gateway->deleteLanguage(2);

        $this->assertQueryResult(
            array(
                array(
                    'count' => '1',
                ),
            ),
            $this->getDatabaseHandler()->createSelectQuery()
                ->select('COUNT( * ) AS count')
                ->from('ezcontent_language')
        );

        $this->assertQueryResult(
            array(
                array(
                    'count' => '0',
                ),
            ),
            $this->getDatabaseHandler()->createSelectQuery()
                ->select('COUNT( * ) AS count')
                ->from('ezcontent_language')
                ->where('id=2')
        );
    }

    /**
     * Returns a ready to test DoctrineDatabase gateway.
     *
     * @return \EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\Language\Gateway\DoctrineDatabase
     */
    protected function getDatabaseGateway()
    {
        if (!isset($this->databaseGateway)) {
            $this->databaseGateway = new DoctrineDatabase(
                $this->getDatabaseHandler()
            );
        }

        return $this->databaseGateway;
    }
}
