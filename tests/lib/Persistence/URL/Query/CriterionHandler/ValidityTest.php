<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\Tests\EzPlatformLegacyStorageEngine\Persistence\URL\Query\CriterionHandler;

use eZ\Publish\API\Repository\Values\URL\Query\Criterion\Validity;
use eZ\Publish\API\Repository\Values\URL\Query\Criterion;
use EzSystems\EzPlatformLegacyStorageEngine\Database\Expression;
use EzSystems\EzPlatformLegacyStorageEngine\Database\SelectQuery;
use EzSystems\EzPlatformLegacyStorageEngine\Persistence\URL\Query\CriteriaConverter;
use EzSystems\EzPlatformLegacyStorageEngine\Persistence\URL\Query\CriterionHandler\Validity as ValidityHandler;

class ValidityTest extends CriterionHandlerTest
{
    /**
     * {@inheritdoc}
     */
    public function testAccept()
    {
        $handler = new ValidityHandler();

        $this->assertHandlerAcceptsCriterion($handler, Validity::class);
        $this->assertHandlerRejectsCriterion($handler, Criterion::class);
    }

    /**
     * {@inheritdoc}
     */
    public function testHandle()
    {
        $criterion = new Validity(true);
        $expected = 'is_valid = :is_valid';

        $expr = $this->createMock(Expression::class);
        $expr
            ->expects($this->once())
            ->method('eq')
            ->with('is_valid', ':is_valid')
            ->willReturn($expected);

        $query = $this->createMock(SelectQuery::class);
        $query->expr = $expr;
        $query
            ->expects($this->once())
            ->method('bindValue')
            ->with($criterion->isValid)
            ->willReturn(':is_valid');

        $converter = $this->createMock(CriteriaConverter::class);

        $handler = new ValidityHandler();
        $actual = $handler->handle($converter, $query, $criterion);

        $this->assertEquals($expected, $actual);
    }
}
