<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\Tests\EzPlatformLegacyStorageEngine\Persistence\URL\Query\CriterionHandler;

use eZ\Publish\API\Repository\Values\URL\Query\Criterion;
use eZ\Publish\API\Repository\Values\URL\Query\Criterion\MatchAll;
use EzSystems\EzPlatformLegacyStorageEngine\Database\SelectQuery;
use EzSystems\EzPlatformLegacyStorageEngine\Persistence\URL\Query\CriteriaConverter;
use EzSystems\EzPlatformLegacyStorageEngine\Persistence\URL\Query\CriterionHandler\MatchAll as MatchAllHandler;

class MatchAllTest extends CriterionHandlerTest
{
    /**
     * {@inheritdoc}
     */
    public function testAccept()
    {
        $handler = new MatchAllHandler();

        $this->assertHandlerAcceptsCriterion($handler, MatchAll::class);
        $this->assertHandlerRejectsCriterion($handler, Criterion::class);
    }

    /**
     * {@inheritdoc}
     */
    public function testHandle()
    {
        $criterion = new MatchAll();
        $expected = ':value';

        $query = $this->createMock(SelectQuery::class);
        $query
            ->expects($this->once())
            ->method('bindValue')
            ->with('1')
            ->willReturn(':value');

        $converter = $this->createMock(CriteriaConverter::class);

        $handler = new MatchAllHandler();
        $actual = $handler->handle($converter, $query, $criterion);

        $this->assertEquals($expected, $actual);
    }
}
