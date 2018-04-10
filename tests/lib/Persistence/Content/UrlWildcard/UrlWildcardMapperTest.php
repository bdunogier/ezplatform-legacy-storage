<?php

/**
 * File contains: EzSystems\Tests\EzPlatformLegacyStorageEngine\Persistence\Content\UrlWildcard\UrlWildcardMapperTest class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\Tests\EzPlatformLegacyStorageEngine\Persistence\Content\UrlWildcard;

use EzSystems\Tests\EzPlatformLegacyStorageEngine\Persistence\TestCase;
use EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\UrlWildcard\Mapper;
use eZ\Publish\SPI\Persistence\Content\UrlWildcard;

/**
 * Test case for UrlWildcard Mapper.
 */
class UrlWildcardMapperTest extends TestCase
{
    /**
     * Test for the createUrlWildcard() method.
     *
     * @covers \EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\UrlWildcard\Mapper::createUrlWildcard
     */
    public function testCreateUrlWildcard()
    {
        $mapper = $this->getMapper();

        $urlWildcard = $mapper->createUrlWildcard(
            'pancake/*',
            'cake/{1}',
            true
        );

        self::assertEquals(
            new UrlWildcard(
                array(
                    'id' => null,
                    'sourceUrl' => '/pancake/*',
                    'destinationUrl' => '/cake/{1}',
                    'forward' => true,
                )
            ),
            $urlWildcard
        );
    }

    /**
     * Test for the extractUrlWildcardFromRow() method.
     *
     * @covers \EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\UrlWildcard\Mapper::extractUrlWildcardFromRow
     */
    public function testExtractUrlWildcardFromRow()
    {
        $mapper = $this->getMapper();
        $row = array(
            'id' => '42',
            'source_url' => 'faq/*',
            'destination_url' => '42',
            'type' => '1',
        );

        $urlWildcard = $mapper->extractUrlWildcardFromRow($row);

        self::assertEquals(
            new UrlWildcard(
                array(
                    'id' => 42,
                    'sourceUrl' => '/faq/*',
                    'destinationUrl' => '/42',
                    'forward' => true,
                )
            ),
            $urlWildcard
        );
    }

    /**
     * Test for the extractUrlWildcardFromRow() method.
     *
     * @covers \EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\UrlWildcard\Mapper::extractUrlWildcardFromRow
     */
    public function testExtractUrlWildcardsFromRows()
    {
        $mapper = $this->getMapper();
        $rows = array(
            array(
                'id' => '24',
                'source_url' => 'contact-information',
                'destination_url' => 'contact',
                'type' => '2',
            ),
            array(
                'id' => '42',
                'source_url' => 'faq/*',
                'destination_url' => '42',
                'type' => '1',
            ),
        );

        $urlWildcards = $mapper->extractUrlWildcardsFromRows($rows);

        self::assertEquals(
            array(
                new UrlWildcard(
                    array(
                        'id' => 24,
                        'sourceUrl' => '/contact-information',
                        'destinationUrl' => '/contact',
                        'forward' => false,
                    )
                ),
                new UrlWildcard(
                    array(
                        'id' => 42,
                        'sourceUrl' => '/faq/*',
                        'destinationUrl' => '/42',
                        'forward' => true,
                    )
                ),
            ),
            $urlWildcards
        );
    }

    /**
     * @return \EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\UrlWildcard\Mapper
     */
    protected function getMapper()
    {
        return new Mapper();
    }
}