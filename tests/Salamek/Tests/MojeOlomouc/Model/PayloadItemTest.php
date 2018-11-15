<?php

declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc\Model;

use Salamek\MojeOlomouc\Enum\ArticleCategoryConsumerFlagEnum;
use Salamek\MojeOlomouc\Enum\RequestActionCodeEnum;
use Salamek\MojeOlomouc\Model\ArticleCategory;
use Salamek\MojeOlomouc\Model\EntityImage;
use Salamek\MojeOlomouc\Model\Identifier;
use Salamek\MojeOlomouc\Model\IModel;
use Salamek\MojeOlomouc\Model\PayloadItem;
use Salamek\Tests\MojeOlomouc\BaseTest;

class PayloadItemTest extends BaseTest
{
    /**
     * @test
     * @dataProvider provideValidConstructorParameters
     * @param IModel $entity
     * @param string $dataKey
     * @param int $action
     */
    public function createRequiredShouldBeGoodTest(
        IModel $entity,
        string $dataKey,
        int $action
    )
    {
        $payloadItem = new PayloadItem(
            $entity,
            $dataKey,
            $action
        );

        $this->assertEquals($entity, $payloadItem->getEntity());
        $this->assertEquals($dataKey, $payloadItem->getDataKey());
        $this->assertEquals($action, $payloadItem->getAction());
        $this->assertEquals($entity->getId(), $payloadItem->getId());
        $this->assertInternalType('array', $payloadItem->toPrimitiveArray());

        $primitiveArrayTest = [
            'action' => [
                'code' => $action,
                'id' => $payloadItem->getId()
            ],
            $dataKey => $entity->toPrimitiveArray()
        ];

        $this->assertEquals($primitiveArrayTest, $payloadItem->toPrimitiveArray());
    }

    /**
     * @test
     * @dataProvider provideInvalidConstructorParameters
     * @expectedException Salamek\MojeOlomouc\Exception\InvalidArgumentException
     * @param IModel $entity
     * @param string $dataKey
     * @param int $action
     */
    public function createOptionalShouldFailOnBadData(
        IModel $entity,
        string $dataKey,
        int $action
    )
    {
        new PayloadItem(
            $entity,
            $dataKey,
            $action
        );
    }

    /**
     * @return array
     */
    public function provideInvalidConstructorParameters(): array
    {
        return [
            [new EntityImage('imageUrl-'.mt_rand()), 'test', RequestActionCodeEnum::ACTION_CODE_DELETE],
            [new EntityImage('imageUrl-'.mt_rand()), 'test', RequestActionCodeEnum::ACTION_CODE_UPDATE],
        ];
    }


    /**
     * @return array
     */
    public function provideValidConstructorParameters(): array
    {
        return [
            [new Identifier(mt_rand()), 'test', RequestActionCodeEnum::ACTION_CODE_DELETE],
            [new Identifier(mt_rand()), 'test', RequestActionCodeEnum::ACTION_CODE_UPDATE],
            [new Identifier(mt_rand()), 'test', RequestActionCodeEnum::ACTION_CODE_CREATE],
        ];
    }
}