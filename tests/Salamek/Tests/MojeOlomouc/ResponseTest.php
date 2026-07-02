<?php

declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;


use Salamek\MojeOlomouc\Model\PlaceCategory;
use Salamek\MojeOlomouc\Response;

class ResponseTest extends BaseTest
{
    #[Test]
#[DataProvider('provideInvalidResponseBody')]

    public function constructFailOnWrongData(string $responseContent): void
    {
        $this->expectException(\Salamek\MojeOlomouc\Exception\InvalidJsonResponseException::class);
        $responseMock = $this->getResponseMockWithBody($responseContent);

        new Response($responseMock);
    }

    #[Test]
#[DataProvider('provideValidResponseBody')]

    public function constructOkOnGoodData(bool $isError, string $responseContent): void
    {
        $responseMock = $this->getResponseMockWithBody($responseContent);
        $responseData = json_decode($responseContent);

        $response = new Response($responseMock);
        $this->assertEquals($isError, $response->isError());
        $this->assertEquals($responseContent, json_encode($response->getRawData()));
        $this->assertEquals((isset($responseData->data) ? $responseData->data : null), $response->getData());
        $this->assertEquals($responseData->message, $response->getMessage());
        $this->assertEquals($responseData->code, $response->getCode());
        $this->assertEquals(200, $response->getHttpCode());
    }

    #[Test]

    public function dataHydratorShouldHydrateData()
    {
        $title = 'title-'.mt_rand();
        $consumerFlags = mt_rand();
        $isVisible = true;
        $id = mt_rand();
        $responseContent = json_encode([
            'isError' => false,
            'message' => 'OK',
            'code' => 0,
            'data' => [
                'tests' => [
                    [
                        'title' => $title,
                        'consumerFlags' => $consumerFlags,
                        'isVisible' => $isVisible,
                        'id' => $id
                    ]
                ]
            ]
        ]);

        $responseMock = $this->getResponseMockWithBody($responseContent);

        $response = new Response($responseMock, ['tests' => new \Salamek\MojeOlomouc\Hydrator\PlaceCategory(PlaceCategory::class)]);
        $data = $response->getData();

        $this->assertArrayHasKey('tests', $data);
        $this->assertNotEmpty($data['tests']);
        $this->assertInstanceOf(PlaceCategory::class, $data['tests'][0]);
        $this->assertObjectHasProperty('title', $data['tests'][0]);
        $this->assertObjectHasProperty('consumerFlags', $data['tests'][0]);
        $this->assertObjectHasProperty('isVisible', $data['tests'][0]);
        $this->assertObjectHasProperty('entityIdentifier', $data['tests'][0]);

        $this->assertEquals($title, $data['tests'][0]->getTitle());
        $this->assertEquals($consumerFlags, $data['tests'][0]->getConsumerFlags());
        $this->assertEquals($isVisible, $data['tests'][0]->getIsVisible());
        $this->assertEquals($id, $data['tests'][0]->getEntityIdentifier());
    }


    #[Test]

    public function idHydratorShouldHydrateId()
    {
        $title = 'title-'.mt_rand();
        $id = mt_rand();
        $responseContent = json_encode([
            'isError' => false,
            'message' => 'OK',
            'code' => 0,
            'data' => [
                [
                    'data' => [
                        'id' => $id
                    ],
                    'code' => 4,
                    'message' => 'OK'
                ]
            ]
        ]);

        $responseMock = $this->getResponseMockWithBody($responseContent);

        $placeCategory = new PlaceCategory($title);

        new Response($responseMock, [], [$placeCategory]);
        $this->assertEquals($id, $placeCategory->getEntityIdentifier());
    }

    /**
     * @return array
     */

    public static function provideInvalidResponseBody(): array
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
            ],
            [
                json_encode([
                    'isError' => false,
                    'message' => 'Oi',
                    'code' => 0
                ])
            ]
        ];
    }

    /**
     * @return array
     */

    public static function provideValidResponseBody(): array
    {
        return [
            [
                false,
                json_encode([
                    'isError' => false,
                    'message' => 'OK',
                    'code' => 0,
                    'data' => []
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
