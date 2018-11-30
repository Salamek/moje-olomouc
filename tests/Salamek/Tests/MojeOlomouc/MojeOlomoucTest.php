<?php

declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc;

use Salamek\MojeOlomouc\MojeOlomouc;
use Salamek\MojeOlomouc\Operation\ArticleCategories;
use Salamek\MojeOlomouc\Operation\Articles;
use Salamek\MojeOlomouc\Operation\EventCategories;
use Salamek\MojeOlomouc\Operation\Events;
use Salamek\MojeOlomouc\Operation\ImportantMessages;
use Salamek\MojeOlomouc\Operation\PlaceCategories;
use Salamek\MojeOlomouc\Operation\Places;


class MojeOlomoucTest extends BaseTest
{

    /**
     * @test
     */
    public function instantiationShouldBeGood(): void
    {
        $mojeOlomouc = new MojeOlomouc($this->getClientMock(), $this->getTestApiKey());
        $this->assertInstanceOf(MojeOlomouc::class, $mojeOlomouc);
    }

    /**
     * @test
     */
    public function createShouldBeGood(): void
    {
        $mojeOlomouc = MojeOlomouc::create($this->getTestApiKey());
        $this->assertInstanceOf(MojeOlomouc::class, $mojeOlomouc);
    }
    
    /**
     * @test
     * @dataProvider provideBadApiKey
     * @expectedException \Salamek\MojeOlomouc\Exception\InvalidArgumentException
     */
    public function checkFailOnBadApiKey(string $apiKey): void
    {
        new MojeOlomouc($this->getClientMock(), $apiKey);
    }

    /**
     * @test
     */
    public function getApiKeyShouldReturnApiKey(): void
    {
        $apiKey = $this->getTestApiKey((string)mt_rand());
        $mojeOlomouc = new MojeOlomouc($this->getClientMock(), $apiKey);
        $this->assertEquals($apiKey, $mojeOlomouc->getApiKey());
    }

    /**
     * @test
     */
    public function getClientShouldReturnClient(): void
    {
        $apiKey = $this->getTestApiKey((string)mt_rand());
        $client = $this->getClientMock();
        $mojeOlomouc = new MojeOlomouc($client, $apiKey);
        $this->assertEquals($client, $mojeOlomouc->getClient());
    }

    /**
     * @test
     * @dataProvider provideValidOperationNames
     * @param string $operationName
     * @param string $expectedOperationClass
     */
    public function getOperationByGetOperationMethodShouldBeGood(string $operationName, string $expectedOperationClass)
    {
        $apiKey = $this->getTestApiKey((string)mt_rand());
        $client = $this->getClientMock();
        $mojeOlomouc = new MojeOlomouc($client, $apiKey);
        $operation = $mojeOlomouc->getOperation($operationName);
        $this->assertInstanceOf($expectedOperationClass, $operation);
    }

    /**
     * @test
     * @dataProvider provideInvalidOperationNames
     * @expectedException \Salamek\MojeOlomouc\Exception\InvalidArgumentException
     * @param string $operationName
     * @param string $expectedOperationClass
     */
    public function getOperationByGetOperationMethodShouldFail(string $operationName, string $expectedOperationClass)
    {
        $apiKey = $this->getTestApiKey((string)mt_rand());
        $client = $this->getClientMock();
        $mojeOlomouc = new MojeOlomouc($client, $apiKey);
        $mojeOlomouc->getOperation($operationName);
    }

    /**
     * @TODO
     * @dataProvider provideValidOperationNames
     * @expectedException \Salamek\MojeOlomouc\Exception\InvalidArgumentException
     * @param string $operationName
     * @param string $expectedOperationClass
     */
    public function getOperationByGetOperationMethodShouldFailOnHydrator(string $operationName, string $expectedOperationClass)
    {
        $apiKey = $this->getTestApiKey((string)mt_rand());
        $client = $this->getClientMock();
        $mojeOlomouc = new MojeOlomouc($client, $apiKey, []);
        $mojeOlomouc->getOperation($operationName);
    }

    /**
     * @test
     * @dataProvider provideValidOperationNames
     * @param string $operationName
     * @param string $expectedOperationClass
     */
    public function getOperationByMagicGetMethodShouldBeGood(string $operationName, string $expectedOperationClass)
    {
        $apiKey = $this->getTestApiKey((string)mt_rand());
        $client = $this->getClientMock();
        $mojeOlomouc = new MojeOlomouc($client, $apiKey);
        $operation = $mojeOlomouc->{$operationName};
        $this->assertInstanceOf($expectedOperationClass, $operation);
    }

    /**
     * @test
     */
    public function getTestGetOperationSingleInstanceMethod()
    {
        $apiKey = $this->getTestApiKey((string)mt_rand());
        $client = $this->getClientMock();
        $mojeOlomouc = new MojeOlomouc($client, $apiKey);
        $operationOne = $mojeOlomouc->getOperation('articles');
        $operationTwo = $mojeOlomouc->getOperation('articles');
        $this->assertEquals($operationOne, $operationTwo);
    }

    /**
     * @return array
     */
    public function provideValidOperationNames(): array
    {
        return [
            ['articleCategories', ArticleCategories::class],
            ['articles', Articles::class],
            ['eventCategories', EventCategories::class],
            ['events', Events::class],
            ['importantMessages', ImportantMessages::class],
            ['placeCategories', PlaceCategories::class],
            ['places', Places::class]
        ];
    }

    /**
     * @return array
     */
    public function provideInvalidOperationNames(): array
    {
        return [
            ['articleCategories'.mt_rand(), ArticleCategories::class],
            ['articles'.mt_rand(), Articles::class],
            ['eventCategories'.mt_rand(), EventCategories::class],
            ['events'.mt_rand(), Events::class],
            ['importantMessages'.mt_rand(), ImportantMessages::class],
            ['placeCategories'.mt_rand(), PlaceCategories::class],
            ['places'.mt_rand(), Places::class]
        ];
    }

    /**
     * @return array
     */
    public function provideBadApiKey(): array
    {
        return [
            [''],
            ['shortApiKey'],
            [$this->getTestApiKey().$this->getTestApiKey()],
        ];
    }
}