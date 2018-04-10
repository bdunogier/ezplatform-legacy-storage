<?php

/**
 * File contains: EzSystems\Tests\EzPlatformLegacyStorageEngine\Persistence\Content\FieldValueConverterRegistryTest class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\Tests\EzPlatformLegacyStorageEngine\Persistence\Content;

use EzSystems\Tests\EzPlatformLegacyStorageEngine\Persistence\TestCase;
use EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\FieldValue\ConverterRegistry as Registry;
use EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\FieldValue\Converter;

/**
 * Test case for FieldValue Converter Registry.
 */
class FieldValueConverterRegistryTest extends TestCase
{
    /**
     * @covers \EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\FieldValue\ConverterRegistry::register
     */
    public function testRegister()
    {
        $converter = $this->getFieldValueConverterMock();
        $registry = new Registry(array('some-type' => $converter));

        $this->assertAttributeSame(
            array(
                'some-type' => $converter,
            ),
            'converterMap',
            $registry
        );
    }

    /**
     * @covers \EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\FieldValue\ConverterRegistry::getConverter
     */
    public function testGetStorage()
    {
        $converter = $this->getFieldValueConverterMock();
        $registry = new Registry(array('some-type' => $converter));

        $res = $registry->getConverter('some-type');

        $this->assertSame(
            $converter,
            $res
        );
    }

    /**
     * @covers \EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\FieldValue\ConverterRegistry::getConverter
     * @covers \EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\FieldValue\Converter\Exception\NotFound
     * @expectedException \EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\FieldValue\Converter\Exception\NotFound
     */
    public function testGetNotFound()
    {
        $registry = new Registry(array());

        $registry->getConverter('not-found');
    }

    /**
     * Returns a mock for Storage.
     *
     * @return Storage
     */
    protected function getFieldValueConverterMock()
    {
        return $this->createMock(Converter::class);
    }
}
