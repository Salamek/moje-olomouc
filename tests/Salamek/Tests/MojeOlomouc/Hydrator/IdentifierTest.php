<?php

declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc\Hydrator;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;


use Salamek\MojeOlomouc\Hydrator\IIdentifier;
use Salamek\Tests\MojeOlomouc\BaseTest;

/**
 * Class IdentifierTest
 * @package Salamek\Tests\MojeOlomouc\Hydrator
 */
class IdentifierTest extends BaseTest
{
    /** @var IIdentifier */
    private $hydrator;

    public function setUp(): void
    {
        parent::setUp();

        $this->hydrator = $this->getHydrator(IIdentifier::class);
    }


    /**
     * @param int $id
     */
#[Test]
#[DataProvider('provideValidConstructorParameters')]

    public function createFromRequiredPrimitiveArrayShouldBeGood(
        int $id
    )
    {
        $identifier = $this->hydrator ->fromPrimitiveArray([
            'id' => $id
        ]);
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