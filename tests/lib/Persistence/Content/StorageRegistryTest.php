<?php

/**
 * File contains: EzSystems\Tests\EzPlatformLegacyStorageEngine\Persistence\Content\StorageRegistryTest class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\Tests\EzPlatformLegacyStorageEngine\Persistence\Content;

use EzSystems\Tests\EzPlatformLegacyStorageEngine\Persistence\TestCase;
use EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\StorageRegistry;
use eZ\Publish\SPI\FieldType\FieldStorage;
use eZ\Publish\Core\FieldType\NullStorage;

/**
 * Test case for StorageRegistry.
 */
class StorageRegistryTest extends TestCase
{
    /**
     * @covers \EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\StorageRegistry::register
     */
    public function testRegister()
    {
        $storage = $this->getStorageMock();
        $registry = new StorageRegistry(array('some-type' => $storage));

        $this->assertAttributeSame(
            array(
                'some-type' => $storage,
            ),
            'storageMap',
            $registry
        );
    }

    /**
     * @covers \EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\StorageRegistry::getStorage
     */
    public function testGetStorage()
    {
        $storage = $this->getStorageMock();
        $registry = new StorageRegistry(array('some-type' => $storage));

        $res = $registry->getStorage('some-type');

        $this->assertSame(
            $storage,
            $res
        );
    }

    /**
     * @covers \EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\StorageRegistry::getStorage
     * @covers \EzSystems\EzPlatformLegacyStorageEngine\Persistence\Exception\StorageNotFound
     */
    public function testGetNotFound()
    {
        $registry = new StorageRegistry(array());
        self::assertInstanceOf(
            NullStorage::class,
            $registry->getStorage('not-found')
        );
    }

    /**
     * Returns a mock for Storage.
     *
     * @return Storage
     */
    protected function getStorageMock()
    {
        return $this->createMock(FieldStorage::class);
    }
}
