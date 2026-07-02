<?php

declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc\Model;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

use Salamek\MojeOlomouc\Enum\ArticleCategoryConsumerFlagEnum;
use Salamek\MojeOlomouc\Hydrator\IIdentifier;
use Salamek\MojeOlomouc\Model\ArticleCategory;
use Salamek\MojeOlomouc\Model\Identifier;
use Salamek\Tests\MojeOlomouc\BaseTest;

class IdentifierTest extends BaseTest
{
    /**
     * @param int $id
     */
#[Test]
#[DataProvider('provideValidConstructorParameters')]

    public function createRequiredShouldBeGoodTest(
        int $id
    )
    {
        $identifier = new Identifier(
            $id
        );

        $this->assertEquals($id, $identifier->getEntityIdentifier());
    }

    /**
     * @return array
     */

    public static function provideValidConstructorParameters(): array
    {
        return [
            [mt_rand()],
            [mt_rand()],
            [mt_rand()],
            [mt_rand()],
            [mt_rand()],
        ];
    }
}