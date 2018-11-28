<?php

declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc\Hydrator;


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

    public function setUp()
    {
        parent::setUp();

        $this->hydrator = $this->getHydrator(IIdentifier::class);
    }


    /**
     * @test
     * @dataProvider provideValidConstructorParameters
     * @param int $id
     */
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
    public function provideValidConstructorParameters(): array
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