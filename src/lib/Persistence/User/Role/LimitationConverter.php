<?php

/**
 * File containing the Role Limitation converter.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformLegacyStorageEngine\Persistence\User\Role;

use eZ\Publish\SPI\Persistence\User\Policy;

/**
 * Limitation converter.
 *
 * Takes care of Converting a Policy limitation from Legacy value to spi value accepted by API.
 */
class LimitationConverter
{
    /**
     * @var \EzSystems\EzPlatformLegacyStorageEngine\Persistence\User\Role\LimitationHandler[]
     */
    protected $limitationHandlers;

    /**
     * Construct from LimitationConverter.
     *
     * @param \EzSystems\EzPlatformLegacyStorageEngine\Persistence\User\Role\LimitationHandler[] $limitationHandlers
     */
    public function __construct(array $limitationHandlers = array())
    {
        $this->limitationHandlers = $limitationHandlers;
    }

    /**
     * Adds handler.
     *
     * @param \EzSystems\EzPlatformLegacyStorageEngine\Persistence\User\Role\LimitationHandler $handler
     */
    public function addHandler(LimitationHandler $handler)
    {
        $this->limitationHandlers[] = $handler;
    }

    /**
     * @param Policy $policy
     */
    public function toLegacy(Policy $policy)
    {
        foreach ($this->limitationHandlers as $limitationHandler) {
            $limitationHandler->toLegacy($policy);
        }
    }

    /**
     * @param Policy $policy
     */
    public function toSPI(Policy $policy)
    {
        foreach ($this->limitationHandlers as $limitationHandler) {
            $limitationHandler->toSPI($policy);
        }
    }
}
