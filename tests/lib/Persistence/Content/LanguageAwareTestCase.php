<?php

/**
 * File contains: EzSystems\Tests\EzPlatformLegacyStorageEngine\Persistence\Content\LanguageAwareTestCase class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\Tests\EzPlatformLegacyStorageEngine\Persistence\Content;

use EzSystems\EzPlatformLegacyStorageEngine\Search\Content\Mapper\FullTextMapper;
use EzSystems\Tests\EzPlatformLegacyStorageEngine\Persistence\TestCase;
use EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\Language\MaskGenerator as LanguageMaskGenerator;
use EzSystems\EzPlatformLegacyStorageEngine\Persistence;
use eZ\Publish\Core\Persistence as CorePersistence;
use eZ\Publish\Core\Search\Common\FieldNameGenerator;
use eZ\Publish\Core\Search\Common\FieldRegistry;

/**
 * Test case for Language aware classes.
 */
abstract class LanguageAwareTestCase extends TestCase
{
    /**
     * Language handler.
     *
     * @var \EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\Language\CachingLanguageHandler
     */
    protected $languageHandler;

    /**
     * Language mask generator.
     *
     * @var \EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\Language\MaskGenerator
     */
    protected $languageMaskGenerator;

    /**
     * Returns a language handler mock.
     *
     * @return \EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\Language\Handler
     */
    protected function getLanguageHandler()
    {
        if (!isset($this->languageHandler)) {
            $this->languageHandler = new LanguageHandlerMock();
        }

        return $this->languageHandler;
    }

    /**
     * Returns a language mask generator.
     *
     * @return \EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\Language\MaskGenerator
     */
    protected function getLanguageMaskGenerator()
    {
        if (!isset($this->languageMaskGenerator)) {
            $this->languageMaskGenerator = new LanguageMaskGenerator(
                $this->getLanguageHandler()
            );
        }

        return $this->languageMaskGenerator;
    }

    /**
     * Return definition-based transformation processor instance.
     *
     * @return CorePersistence\TransformationProcessor\DefinitionBased
     */
    protected function getDefinitionBasedTransformationProcessor()
    {
        return new CorePersistence\TransformationProcessor\DefinitionBased(
            new CorePersistence\TransformationProcessor\DefinitionBased\Parser(),
            new CorePersistence\TransformationProcessor\PcreCompiler(
                new CorePersistence\Utf8Converter()
            ),
            glob(__DIR__ . '/../../../../Persistence/Tests/TransformationProcessor/_fixtures/transformations/*.tr')
        );
    }

    /**
     * @var \eZ\Publish\Core\Search\Common\FieldNameGenerator|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $fieldNameGeneratorMock;

    /**
     * @return \eZ\Publish\Core\Search\Common\FieldNameGenerator|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getFieldNameGeneratorMock()
    {
        if (!isset($this->fieldNameGeneratorMock)) {
            $this->fieldNameGeneratorMock = $this->createMock(FieldNameGenerator::class);
        }

        return $this->fieldNameGeneratorMock;
    }

    /**
     * @param \EzSystems\EzPlatformLegacyStorageEngine\Persistence\Content\Type\Handler $contentTypeHandler
     * @return \EzSystems\EzPlatformLegacyStorageEngine\Search\Content\Mapper\FullTextMapper
     */
    protected function getFullTextMapper(Persistence\Content\Type\Handler $contentTypeHandler)
    {
        return new FullTextMapper(
            $this->createMock(FieldRegistry::class),
            $contentTypeHandler
        );
    }

    /**
     * Get FullText search configuration.
     */
    protected function getFullTextSearchConfiguration()
    {
        return [
            'stopWordThresholdFactor' => 0.66,
            'enableWildcards' => true,
            'commands' => [
                'apostrophe_normalize',
                'apostrophe_to_doublequote',
                'ascii_lowercase',
                'ascii_search_cleanup',
                'cyrillic_diacritical',
                'cyrillic_lowercase',
                'cyrillic_search_cleanup',
                'cyrillic_transliterate_ascii',
                'doublequote_normalize',
                'endline_search_normalize',
                'greek_diacritical',
                'greek_lowercase',
                'greek_normalize',
                'greek_transliterate_ascii',
                'hebrew_transliterate_ascii',
                'hyphen_normalize',
                'inverted_to_normal',
                'latin1_diacritical',
                'latin1_lowercase',
                'latin1_transliterate_ascii',
                'latin-exta_diacritical',
                'latin-exta_lowercase',
                'latin-exta_transliterate_ascii',
                'latin_lowercase',
                'latin_search_cleanup',
                'latin_search_decompose',
                'math_to_ascii',
                'punctuation_normalize',
                'space_normalize',
                'special_decompose',
                'specialwords_search_normalize',
                'tab_search_normalize',
            ],
        ];
    }
}
