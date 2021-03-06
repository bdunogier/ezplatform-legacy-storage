<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\Tests\EzPlatformLegacyStorageEngine\Persistence\URL\Query\CriterionHandler;

use eZ\Publish\API\Repository\Values\URL\Query\Criterion;
use eZ\Publish\API\Repository\Values\URL\Query\Criterion\LogicalOr;
use EzSystems\EzPlatformLegacyStorageEngine\Database\Expression;
use EzSystems\EzPlatformLegacyStorageEngine\Database\SelectQuery;
use EzSystems\EzPlatformLegacyStorageEngine\Persistence\URL\Query\CriteriaConverter;
use EzSystems\EzPlatformLegacyStorageEngine\Persistence\URL\Query\CriterionHandler\LogicalOr as LogicalOrHandler;

class LogicalOrTest extends CriterionHandlerTest
{
    /**
     * {@inheritdoc}
     */
    public function testAccept()
    {
        $handler = new LogicalOrHandler();

        $this->assertHandlerAcceptsCriterion($handler, LogicalOr::class);
        $this->assertHandlerRejectsCriterion($handler, Criterion::class);
    }

    /**
     * {@inheritdoc}
     */
    public function testHandle()
    {
        $foo = $this->createMock(Criterion::class);
        $bar = $this->createMock(Criterion::class);

        $fooExpr = 'FOO';
        $barExpr = 'BAR';

        $expected = 'FOO OR BAR';

        $expr = $this->createMock(Expression::class);
        $expr
            ->expects($this->once())
            ->method('lOr')
            ->with([$fooExpr, $barExpr])
            ->willReturn($expected);

        $query = $this->createMock(SelectQuery::class);
        $query->expr = $expr;

        $converter = $this->createMock(CriteriaConverter::class);
        $converter
            ->expects($this->at(0))
            ->method('convertCriteria')
            ->with($query, $foo)
            ->willReturn($fooExpr);
        $converter
            ->expects($this->at(1))
            ->method('convertCriteria')
            ->with($query, $bar)
            ->willReturn($barExpr);

        $handler = new LogicalOrHandler();
        $actual = $handler->handle(
            $converter, $query, new LogicalOr([$foo, $bar])
        );

        $this->assertEquals($expected, $actual);
    }
}
