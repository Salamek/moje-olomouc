<?php

declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc\Model;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

use Salamek\MojeOlomouc\Enum\ArticleCategoryConsumerFlagEnum;
use Salamek\MojeOlomouc\Enum\RequestActionCodeEnum;
use Salamek\MojeOlomouc\Hydrator\IHydrator;
use Salamek\MojeOlomouc\Model\ArticleCategory;
use Salamek\MojeOlomouc\Model\EntityImage;
use Salamek\MojeOlomouc\Model\Identifier;
use Salamek\MojeOlomouc\Model\IModel;
use Salamek\MojeOlomouc\Model\PayloadItem;
use Salamek\Tests\MojeOlomouc\BaseTest;

class PayloadItemTest extends BaseTest
{
    /**
     * @param IModel $entity
     * @param string $dataKey
     * @param int $action
     * @param IHydrator $hydrator
     */
#[Test]
#[DataProvider('provideValidConstructorParameters')]

    public function createRequiredShouldBeGoodTest(
        IModel $entity,
        string $dataKey,
        int $action,
        IHydrator $hydrator
    )
    {
        $payloadItem = new PayloadItem(
            $entity,
            $dataKey,
            $action,
            $hydrator
        );

        $this->assertEquals($entity, $payloadItem->getEntity());
        $this->assertEquals($dataKey, $payloadItem->getDataKey());
        $this->assertEquals($action, $payloadItem->getAction());
        $this->assertEquals($entity->getEntityIdentifier(), $payloadItem->getId());
        $this->assertIsArray($payloadItem->toPrimitiveArray());

        $primitiveArrayTest = [
            'action' => [
                'code' => $action,
                'id' => $payloadItem->getId()
            ],
            $dataKey => $hydrator->toPrimitiveArray($entity)
        ];

        $this->assertEquals($primitiveArrayTest, $payloadItem->toPrimitiveArray());
    }

    /**
     * @param IModel $entity
     * @param string $dataKey
     * @param int $action
     * @param IHydrator $hydrator
     */
#[Test]
#[DataProvider('provideInvalidConstructorParameters')]

    public function createOptionalShouldFailOnBadData(
        IModel $entity,
        string $dataKey,
        int $action,
        IHydrator $hydrator
    )
    {
        $this->expectException(\Salamek\MojeOlomouc\Exception\InvalidArgumentException::class);
        new PayloadItem(
            $entity,
            $dataKey,
            $action,
            $hydrator
        );
    }

    /**
     * @return array
     */

    public static function provideInvalidConstructorParameters(): array
    {
        return [
            [new EntityImage('imageUrl-'.mt_rand()), 'test', RequestActionCodeEnum::ACTION_CODE_DELETE, new \Salamek\MojeOlomouc\Hydrator\EntityImage(EntityImage::class)],
            [new EntityImage('imageUrl-'.mt_rand()), 'test', RequestActionCodeEnum::ACTION_CODE_UPDATE, new \Salamek\MojeOlomouc\Hydrator\EntityImage(EntityImage::class)],
        ];
    }


    /**
     * @return array
     */

    public static function provideValidConstructorParameters(): array
    {
        return [
            [new Identifier(mt_rand()), 'test', RequestActionCodeEnum::ACTION_CODE_DELETE, new \Salamek\MojeOlomouc\Hydrator\Identifier(Identifier::class)],
            [new Identifier(mt_rand()), 'test', RequestActionCodeEnum::ACTION_CODE_UPDATE, new \Salamek\MojeOlomouc\Hydrator\Identifier(Identifier::class)],
            [new Identifier(mt_rand()), 'test', RequestActionCodeEnum::ACTION_CODE_CREATE, new \Salamek\MojeOlomouc\Hydrator\Identifier(Identifier::class)],
        ];
    }
}