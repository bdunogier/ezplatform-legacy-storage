<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\Tests\EzPlatformLegacyStorageEngine\Persistence\URL\Query\CriterionHandler;

use eZ\Publish\API\Repository\Values\URL\Query\Criterion;
use eZ\Publish\API\Repository\Values\URL\Query\Criterion\LogicalNot;
use EzSystems\EzPlatformLegacyStorageEngine\Database\Expression;
use EzSystems\EzPlatformLegacyStorageEngine\Database\SelectQuery;
use EzSystems\EzPlatformLegacyStorageEngine\Persistence\URL\Query\CriteriaConverter;
use EzSystems\EzPlatformLegacyStorageEngine\Persistence\URL\Query\CriterionHandler\LogicalNot as LogicalNotHandler;

class LogicalNotTest extends CriterionHandlerTest
{
    /**
     * {@inheritdoc}
     */
    public function testAccept()
    {
        $handler = new LogicalNotHandler();

        $this->assertHandlerAcceptsCriterion($handler, LogicalNot::class);
        $this->assertHandlerRejectsCriterion($handler, Criterion::class);
    }

    /**
     * {@inheritdoc}
     */
    public function testHandle()
    {
        $foo = $this->createMock(Criterion::class);
        $fooExpr = 'FOO';
        $expected = 'NOT FOO';

        $expr = $this->createMock(Expression::class);
        $expr
            ->expects($this->once())
            ->method('not')
            ->with($fooExpr)
            ->willReturn($expected);

        $query = $this->createMock(SelectQuery::class);
        $query->expr = $expr;

        $converter = $this->createMock(CriteriaConverter::class);
        $converter
            ->expects($this->at(0))
            ->method('convertCriteria')
            ->with($query, $foo)
            ->willReturn($fooExpr);

        $handler = new LogicalNotHandler();
        $actual = $handler->handle(
            $converter, $query, new LogicalNot($foo)
        );

        $this->assertEquals($expected, $actual);
    }
}
