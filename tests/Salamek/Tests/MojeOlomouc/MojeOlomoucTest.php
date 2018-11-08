<?php

declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc;

use PHPUnit\Framework\TestCase;
use Salamek\MojeOlomouc\Exception\InvalidArgumentException;
use Salamek\MojeOlomouc\MojeOlomouc;

class MojeOlomoucTest extends TestCase
{
    /** @test */
    public function instantiationShouldBeGood()
    {
        $mojeOlomouc = new MojeOlomouc($this->getClientMock(), $this->getTestApiKey());
        $this->assertInstanceOf(MojeOlomouc::class, $mojeOlomouc);
    }

    /** @test */
    public function createShouldBeGood()
    {
        $mojeOlomouc = MojeOlomouc::create($this->getTestApiKey());
        $this->assertInstanceOf(MojeOlomouc::class, $mojeOlomouc);
    }
    
    /**
     * @test
     * @dataProvider provideBadApiKey
     * @expectedException InvalidArgumentException
     */
    public function checkFailOnBadApiKey($apiKey)
    {
        new MojeOlomouc($this->getClientMock(), $apiKey);
    }

    /** @test */
    public function getApiKeyShouldReturnApiKey()
    {
        $apiKey = $this->getTestApiKey((string)mt_rand());
        $mojeOlomouc = new MojeOlomouc($this->getClientMock(), $apiKey);
        $this->assertEquals($apiKey, $mojeOlomouc->getApiKey());
    }
    
    private function getClientMock()
    {
        return $this->createMock('GuzzleHttp\ClientInterface');
    }

    private function getTestApiKey($input = 'testKey')
    {
        return hash('sha256', $input);
    }

    public function provideBadApiKey()
    {
        return [
            [''],
            ['shortApiKey'],
            [$this->getTestApiKey().$this->getTestApiKey()],
        ];
    }
}