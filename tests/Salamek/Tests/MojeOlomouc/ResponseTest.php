<?php

declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc;


use PHPUnit\Framework\TestCase;
use Salamek\MojeOlomouc\Response;

class ResponseTest extends TestCase
{
    /**
     * @dataProvider provideConstructorParameters
     */
    public function testConstruct($isError, $errors)
    {
        $response = new Response($this->getResponseMock(), $errors);
        $this->assertEquals($isError, $response->isError());
        $this->assertEquals($errors, $response->getErrors());
    }

    private function getResponseMock()
    {
        return $this->createMock('Psr\Http\Message\ResponseInterface');
    }

    public function provideConstructorParameters()
    {
        return [
            [false, []],
            [true, ['This is an error message']],
            [true, ['Ceci est un message d\'ééérreur']],
        ];
    }
}
