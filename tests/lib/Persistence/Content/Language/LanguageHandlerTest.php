<?php

/**
 * File contains: EzSystems\Tests\EzPlatformLegacyStorageEngine\Persistence\Content\Language\LanguageHandlerTest class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\Tests\EzPlatformLegacyStorageEngine\Persistence\Content\Language;

use EzSystems\Tests\EzPlatformLegacyStorageEngine\Persistence\TestCase;
use eZ\Publish\SPI\Persistence\Content\Language;
use EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\Language\Handler;
use eZ\Publish\SPI\Persistence\Content\Language\CreateStruct as SPILanguageCreateStruct;
use EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\Language\Mapper as LanguageMapper;
use EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\Language\Gateway as LanguageGateway;

/**
 * Test case for Language Handler.
 */
class LanguageHandlerTest extends TestCase
{
    /**
     * Language handler.
     *
     * @var \EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\Language\Handler
     */
    protected $languageHandler;

    /**
     * Language gateway mock.
     *
     * @var \EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\Language\Gateway
     */
    protected $gatewayMock;

    /**
     * Language mapper mock.
     *
     * @var \EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\Language\Mapper
     */
    protected $mapperMock;

    /**
     * @covers \EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\Language\Handler::create
     */
    public function testCreate()
    {
        $handler = $this->getLanguageHandler();

        $mapperMock = $this->getMapperMock();
        $mapperMock->expects($this->once())
            ->method('createLanguageFromCreateStruct')
            ->with(
                $this->isInstanceOf(
                    SPILanguageCreateStruct::class
                )
            )->will($this->returnValue(new Language()));

        $gatewayMock = $this->getGatewayMock();
        $gatewayMock->expects($this->once())
            ->method('insertLanguage')
            ->with(
                $this->isInstanceOf(
                    Language::class
                )
            )->will($this->returnValue(2));

        $createStruct = $this->getCreateStructFixture();

        $result = $handler->create($createStruct);

        $this->assertInstanceOf(
            Language::class,
            $result
        );
        $this->assertEquals(
            2,
            $result->id
        );
    }

    /**
     * Returns a Language CreateStruct.
     *
     * @return \eZ\Publish\SPI\Persistence\Content\Language\CreateStruct
     */
    protected function getCreateStructFixture()
    {
        return new Language\CreateStruct();
    }

    /**
     * @covers \EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\Language\Handler::update
     */
    public function testUpdate()
    {
        $handler = $this->getLanguageHandler();

        $gatewayMock = $this->getGatewayMock();
        $gatewayMock->expects($this->once())
            ->method('updateLanguage')
            ->with($this->isInstanceOf(Language::class));

        $handler->update($this->getLanguageFixture());
    }

    /**
     * Returns a Language.
     *
     * @return \eZ\Publish\SPI\Persistence\Content\Language
     */
    protected function getLanguageFixture()
    {
        return new Language();
    }

    /**
     * @covers \EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\Language\Handler::load
     */
    public function testLoad()
    {
        $handler = $this->getLanguageHandler();
        $mapperMock = $this->getMapperMock();
        $gatewayMock = $this->getGatewayMock();

        $gatewayMock->expects($this->once())
            ->method('loadLanguageData')
            ->with($this->equalTo(2))
            ->will($this->returnValue(array()));

        $mapperMock->expects($this->once())
            ->method('extractLanguagesFromRows')
            ->with($this->equalTo(array()))
            ->will($this->returnValue(array(new Language())));

        $result = $handler->load(2);

        $this->assertInstanceOf(
            Language::class,
            $result
        );
    }

    /**
     * @covers \EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\Language\Handler::load
     * @expectedException \eZ\Publish\API\Repository\Exceptions\NotFoundException
     */
    public function testLoadFailure()
    {
        $handler = $this->getLanguageHandler();
        $mapperMock = $this->getMapperMock();
        $gatewayMock = $this->getGatewayMock();

        $gatewayMock->expects($this->once())
            ->method('loadLanguageData')
            ->with($this->equalTo(2))
            ->will($this->returnValue(array()));

        $mapperMock->expects($this->once())
            ->method('extractLanguagesFromRows')
            ->with($this->equalTo(array()))
            // No language extracted
            ->will($this->returnValue(array()));

        $result = $handler->load(2);
    }

    /**
     * @covers \EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\Language\Handler::loadByLanguageCode
     */
    public function testLoadByLanguageCode()
    {
        $handler = $this->getLanguageHandler();
        $mapperMock = $this->getMapperMock();
        $gatewayMock = $this->getGatewayMock();

        $gatewayMock->expects($this->once())
            ->method('loadLanguageDataByLanguageCode')
            ->with($this->equalTo('eng-US'))
            ->will($this->returnValue(array()));

        $mapperMock->expects($this->once())
            ->method('extractLanguagesFromRows')
            ->with($this->equalTo(array()))
            ->will($this->returnValue(array(new Language())));

        $result = $handler->loadByLanguageCode('eng-US');

        $this->assertInstanceOf(
            Language::class,
            $result
        );
    }

    /**
     * @covers \EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\Language\Handler::loadByLanguageCode
     * @expectedException \eZ\Publish\API\Repository\Exceptions\NotFoundException
     */
    public function testLoadByLanguageCodeFailure()
    {
        $handler = $this->getLanguageHandler();
        $mapperMock = $this->getMapperMock();
        $gatewayMock = $this->getGatewayMock();

        $gatewayMock->expects($this->once())
            ->method('loadLanguageDataByLanguageCode')
            ->with($this->equalTo('eng-US'))
            ->will($this->returnValue(array()));

        $mapperMock->expects($this->once())
            ->method('extractLanguagesFromRows')
            ->with($this->equalTo(array()))
            // No language extracted
            ->will($this->returnValue(array()));

        $result = $handler->loadByLanguageCode('eng-US');
    }

    /**
     * @covers \EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\Language\Handler::loadAll
     */
    public function testLoadAll()
    {
        $handler = $this->getLanguageHandler();
        $mapperMock = $this->getMapperMock();
        $gatewayMock = $this->getGatewayMock();

        $gatewayMock->expects($this->once())
            ->method('loadAllLanguagesData')
            ->will($this->returnValue(array()));

        $mapperMock->expects($this->once())
            ->method('extractLanguagesFromRows')
            ->with($this->equalTo(array()))
            ->will($this->returnValue(array(new Language())));

        $result = $handler->loadAll();

        $this->assertInternalType(
            'array',
            $result
        );
    }

    /**
     * @covers \EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\Language\Handler::delete
     */
    public function testDeleteSuccess()
    {
        $handler = $this->getLanguageHandler();
        $gatewayMock = $this->getGatewayMock();

        $gatewayMock->expects($this->once())
            ->method('canDeleteLanguage')
            ->with($this->equalTo(2))
            ->will($this->returnValue(true));
        $gatewayMock->expects($this->once())
            ->method('deleteLanguage')
            ->with($this->equalTo(2));

        $result = $handler->delete(2);
    }

    /**
     * @covers \EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\Language\Handler::delete
     * @expectedException \LogicException
     */
    public function testDeleteFail()
    {
        $handler = $this->getLanguageHandler();
        $gatewayMock = $this->getGatewayMock();

        $gatewayMock->expects($this->once())
            ->method('canDeleteLanguage')
            ->with($this->equalTo(2))
            ->will($this->returnValue(false));
        $gatewayMock->expects($this->never())
            ->method('deleteLanguage');

        $result = $handler->delete(2);
    }

    /**
     * Returns the language handler to test.
     *
     * @return \EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\Language\Handler
     */
    protected function getLanguageHandler()
    {
        if (!isset($this->languageHandler)) {
            $this->languageHandler = new Handler(
                $this->getGatewayMock(),
                $this->getMapperMock()
            );
        }

        return $this->languageHandler;
    }

    /**
     * Returns a language mapper mock.
     *
     * @return \EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\Language\Mapper
     */
    protected function getMapperMock()
    {
        if (!isset($this->mapperMock)) {
            $this->mapperMock = $this->createMock(LanguageMapper::class);
        }

        return $this->mapperMock;
    }

    /**
     * Returns a mock for the language gateway.
     *
     * @return \EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\Language\Gateway
     */
    protected function getGatewayMock()
    {
        if (!isset($this->gatewayMock)) {
            $this->gatewayMock = $this->getMockForAbstractClass(LanguageGateway::class);
        }

        return $this->gatewayMock;
    }
}
