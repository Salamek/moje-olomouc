<?php

declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc;


use Salamek\MojeOlomouc\Response;

class ResponseTest extends BaseTest
{
    /**
     * @dataProvider provideInvalidResponseBody
     * @expectedException Salamek\MojeOlomouc\Exception\InvalidJsonResponseException
     */
    public function testConstructFailOnWrongData(string $responseContent): void
    {
        $responseMock = $this->getResponseMockWithBody($responseContent);

        new Response($responseMock);
    }

    /**
     * @dataProvider provideValidResponseBody
     */
    public function testConstructOkOnGoodData(bool $isError, string $responseContent): void
    {
        $responseMock = $this->getResponseMockWithBody($responseContent);

        $response = new Response($responseMock);
        $this->assertEquals($isError, $response->isError());
        $this->assertEquals($responseContent, json_encode($response->getData()));
        $this->assertEquals($isError, !empty($response->getErrors()));
    }
 
    public function provideInvalidResponseBody(): array
    {
        return [
            [
                json_encode([])
            ],
            [
                'This is invalid body response'
            ],
            [
                json_encode([
                    'lol' => 'Ceci est un message d\'ééérreur'
                ])
            ],
            [
                json_encode([
                    'isError' => false,
                ])
            ],
            [
                json_encode([
                    'isError' => false,
                    'message' => 'Oi'
                ])
            ]
        ];
    }

    public function provideValidResponseBody(): array
    {
        return [
            [
                false,
                json_encode([
                    'isError' => false,
                    'message' => 'OK',
                    'code' => 0
                ])
            ],
            [
                true,
                json_encode([
                    'isError' => true,
                    'message' => 'ERR',
                    'code' => -128
                ])
            ]
        ];
    }
}
