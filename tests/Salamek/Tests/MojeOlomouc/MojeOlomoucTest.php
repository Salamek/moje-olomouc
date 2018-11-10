<?php

declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc;

use Salamek\MojeOlomouc\MojeOlomouc;


class MojeOlomoucTest extends BaseTest
{

    /** @test */
    public function instantiationShouldBeGood(): void
    {
        $mojeOlomouc = new MojeOlomouc($this->getClientMock(), $this->getTestApiKey());
        $this->assertInstanceOf(MojeOlomouc::class, $mojeOlomouc);
    }

    /** @test */
    public function createShouldBeGood(): void
    {
        $mojeOlomouc = MojeOlomouc::create($this->getTestApiKey());
        $this->assertInstanceOf(MojeOlomouc::class, $mojeOlomouc);
    }
    
    /**
     * @test
     * @dataProvider provideBadApiKey
     * @expectedException Salamek\MojeOlomouc\Exception\InvalidArgumentException
     */
    public function checkFailOnBadApiKey(string $apiKey): void
    {
        new MojeOlomouc($this->getClientMock(), $apiKey);
    }

    /** @test */
    public function getApiKeyShouldReturnApiKey(): void
    {
        $apiKey = $this->getTestApiKey((string)mt_rand());
        $mojeOlomouc = new MojeOlomouc($this->getClientMock(), $apiKey);
        $this->assertEquals($apiKey, $mojeOlomouc->getApiKey());
    }

    /** @test */
    public function getClientShouldReturnClient(): void
    {
        $apiKey = $this->getTestApiKey((string)mt_rand());
        $client = $this->getClientMock();
        $mojeOlomouc = new MojeOlomouc($client, $apiKey);
        $this->assertEquals($client, $mojeOlomouc->getClient());
    }
    
    public function provideBadApiKey(): array
    {
        return [
            [''],
            ['shortApiKey'],
            [$this->getTestApiKey().$this->getTestApiKey()],
        ];
    }
}