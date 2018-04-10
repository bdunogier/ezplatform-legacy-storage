<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\Tests\EzPlatformLegacyStorageEngine\Persistence\URL\Query\CriterionHandler;

use eZ\Publish\API\Repository\Values\URL\Query\Criterion;
use eZ\Publish\API\Repository\Values\URL\Query\Criterion\MatchNone;
use EzSystems\EzPlatformLegacyStorageEngine\Database\Expression;
use EzSystems\EzPlatformLegacyStorageEngine\Database\SelectQuery;
use EzSystems\EzPlatformLegacyStorageEngine\Persistence\URL\Query\CriteriaConverter;
use EzSystems\EzPlatformLegacyStorageEngine\Persistence\URL\Query\CriterionHandler\MatchNone as MatchNoneHandler;

class MatchNoneTest extends CriterionHandlerTest
{
    /**
     * {@inheritdoc}
     */
    public function testAccept()
    {
        $handler = new MatchNoneHandler();

        $this->assertHandlerAcceptsCriterion($handler, MatchNone::class);
        $this->assertHandlerRejectsCriterion($handler, Criterion::class);
    }

    /**
     * {@inheritdoc}
     */
    public function testHandle()
    {
        $criterion = new MatchNone();
        $expected = 'NOT :value';

        $expr = $this->createMock(Expression::class);
        $expr
            ->expects($this->once())
            ->method('not')
            ->with(':value')
            ->willReturn($expected);

        $query = $this->createMock(SelectQuery::class);
        $query->expr = $expr;
        $query
            ->expects($this->once())
            ->method('bindValue')
            ->with('1')
            ->willReturn(':value');

        $converter = $this->createMock(CriteriaConverter::class);

        $handler = new MatchNoneHandler();
        $actual = $handler->handle($converter, $query, $criterion);

        $this->assertEquals($expected, $actual);
    }
}
