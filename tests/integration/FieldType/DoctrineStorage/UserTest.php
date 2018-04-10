<?php

/**
 * This file is part of the eZ Publish Kernel package.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\IntegrationTests\EzPlatformLegacyStorageEngine\FieldType\DoctrineStorage;

use eZ\Publish\Core\FieldType\Tests\Integration\User\UserStorage\UserStorageGatewayTest;
use eZ\Publish\Core\FieldType\User\UserStorage\Gateway\User;

class UserTest extends UserStorageGatewayTest
{
    /**
     * @return \eZ\Publish\Core\FieldType\User\UserStorage\Gateway
     */
    protected function getGateway()
    {
        $dbHandler = $this->getDatabaseHandler();

        return new User($dbHandler->getConnection());
    }
}
