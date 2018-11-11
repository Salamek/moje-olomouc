<?php

declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc;

use Salamek\MojeOlomouc\Enum\PlaceApproveStateEnum;
use Salamek\MojeOlomouc\Enum\RequestActionCodeEnum;
use Salamek\MojeOlomouc\Model\Place;
use Salamek\MojeOlomouc\Model\EntityImage;
use Salamek\MojeOlomouc\Model\IPlace;
use Salamek\MojeOlomouc\Operation\Places;
use Salamek\MojeOlomouc\Request;
use Salamek\MojeOlomouc\Response;


class PlacesTest extends BaseTest
{
    /**
     * @test
     * @dataProvider provideGetAllConstructorParameters
     * @param \DateTimeInterface|null $fromUpdatedAt
     * @param bool $showDeleted
     * @param bool $onlyApproved
     * @param bool $onlyVisible
     * @param bool $extraFields
     */
    public function getAllShouldBeGoodTest(\DateTimeInterface $fromUpdatedAt = null, bool $showDeleted = false, bool $onlyApproved = true, bool $onlyVisible = true, bool $extraFields = false): void
    {
        $apiKey = $this->getTestApiKey();

        $catchType = null;
        $catchUri = null;
        $catchRequestInfo = null;
        $responseData = $this->getFakeResponseData();
        $responseDataString = json_encode($responseData);

        $response = $this->getResponseMockWithBody($responseDataString);

        $client = $this->getClientMock();
        $client->expects($this->once())
            ->method('request')
            ->will($this->returnCallback(function ($type, $uri, $requestInfo) use (&$catchRequestInfo, &$catchType, &$catchUri, $response) {
                $catchType = $type;
                $catchUri = $uri;
                $catchRequestInfo = $requestInfo;
                return $response;
            }));


        $request = new Request($client, $apiKey);

        $places = new Places($request);
        $response = $places->getAll(
            $fromUpdatedAt,
            $showDeleted,
            $onlyApproved,
            $onlyVisible,
            $extraFields
        );

        $this->assertInternalType('array', $catchRequestInfo['query']);
        $this->assertArrayHasKey('fromUpdatedAt', $catchRequestInfo['query']);
        $this->assertArrayHasKey('showDeleted', $catchRequestInfo['query']);
        $this->assertArrayHasKey('onlyApproved', $catchRequestInfo['query']);
        $this->assertArrayHasKey('onlyVisible', $catchRequestInfo['query']);
        $this->assertArrayHasKey('extraFields', $catchRequestInfo['query']);
        $this->assertEquals(($fromUpdatedAt ? $fromUpdatedAt->format(\DateTime::ISO8601) : null), $catchRequestInfo['query']['fromUpdatedAt']);
        $this->assertEquals($showDeleted, $catchRequestInfo['query']['showDeleted']);
        $this->assertEquals($onlyApproved, $catchRequestInfo['query']['onlyApproved']);
        $this->assertEquals($onlyVisible, $catchRequestInfo['query']['onlyVisible']);
        $this->assertEquals($extraFields, $catchRequestInfo['query']['extraFields']);
        $this->assertEquals('GET', $catchType);
        $this->assertEquals('Basic '.$apiKey, $catchRequestInfo['headers']['Authorization']);
        $this->assertEquals('/api/export/places', $catchUri);
        $this->assertInstanceOf(Response::class, $response);
    }


    /**
     * @test
     * @dataProvider provideCreateConstructorParameters
     * @param IPlace $place
     */
    public function createShouldBeGoodTest(IPlace $place)
    {
        $apiKey = $this->getTestApiKey();

        $catchType = null;
        $catchUri = null;
        $catchRequestInfo = null;
        $responseData = $this->getFakeResponseData();
        $responseDataString = json_encode($responseData);

        $response = $this->getResponseMockWithBody($responseDataString);

        $client = $this->getClientMock();
        $client->expects($this->once())
            ->method('request')
            ->will($this->returnCallback(function ($type, $uri, $requestInfo) use (&$catchRequestInfo, &$catchType, &$catchUri, $response) {
                $catchType = $type;
                $catchUri = $uri;
                $catchRequestInfo = $requestInfo;
                return $response;
            }));

        $request = new Request($client, $apiKey);
        $places = new Places($request);
        $response = $places->create($place);

        $primitiveImages = [];
        foreach ($place->getImages() AS $image)
        {
            $primitiveImages[] = $image->toPrimitiveArray();
        }

        $this->assertInternalType('array', $catchRequestInfo['json']);
        $this->assertArrayHasKey('place', $catchRequestInfo['json']);
        $this->assertArrayHasKey('title', $catchRequestInfo['json']['place']);
        $this->assertArrayHasKey('description', $catchRequestInfo['json']['place']);
        $this->assertArrayHasKey('address', $catchRequestInfo['json']['place']);
        $this->assertArrayHasKey('lat', $catchRequestInfo['json']['place']);
        $this->assertArrayHasKey('lon', $catchRequestInfo['json']['place']);
        $this->assertArrayHasKey('categoryId', $catchRequestInfo['json']['place']);
        $this->assertArrayHasKey('images', $catchRequestInfo['json']['place']);
        $this->assertArrayHasKey('attachmentUrl', $catchRequestInfo['json']['place']);
        $this->assertArrayHasKey('isVisible', $catchRequestInfo['json']['place']);
        $this->assertArrayHasKey('approveState', $catchRequestInfo['json']['place']);
        $this->assertEquals($place->getTitle(), $catchRequestInfo['json']['place']['title']);
        $this->assertEquals($place->getDescription(), $catchRequestInfo['json']['place']['description']);
        $this->assertEquals($place->getAddress(), $catchRequestInfo['json']['place']['address']);
        $this->assertEquals($place->getLat(), $catchRequestInfo['json']['place']['lat']);
        $this->assertEquals($place->getLon(), $catchRequestInfo['json']['place']['lon']);
        $this->assertEquals($place->getCategoryId(), $catchRequestInfo['json']['place']['categoryId']);
        $this->assertEquals($primitiveImages, $catchRequestInfo['json']['place']['images']);
        $this->assertEquals($place->getAttachmentUrl(), $catchRequestInfo['json']['place']['attachmentUrl']);
        $this->assertEquals($place->getIsVisible(), $catchRequestInfo['json']['place']['isVisible']);
        $this->assertEquals($place->getApproveState(), $catchRequestInfo['json']['place']['approveState']);
        $this->assertEquals('POST', $catchType);
        $this->assertEquals('Basic '.$apiKey, $catchRequestInfo['headers']['Authorization']);
        $this->assertEquals('/api/import/places', $catchUri);
        $this->assertEquals(RequestActionCodeEnum::ACTION_CODE_CREATE, $catchRequestInfo['json']['action']['code']);
        $this->assertEquals(null, $catchRequestInfo['json']['action']['id']);
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @test
     * @dataProvider provideUpdateConstructorParameters
     * @param IPlace $place
     * @param int|null $id
     */
    public function updateShouldBeGoodTest(IPlace $place, int $id = null)
    {
        $apiKey = $this->getTestApiKey();

        $catchType = null;
        $catchUri = null;
        $catchRequestInfo = null;
        $responseData = $this->getFakeResponseData();
        $responseDataString = json_encode($responseData);

        $response = $this->getResponseMockWithBody($responseDataString);

        $client = $this->getClientMock();
        $client->expects($this->once())
            ->method('request')
            ->will($this->returnCallback(function ($type, $uri, $requestInfo) use (&$catchRequestInfo, &$catchType, &$catchUri, $response) {
                $catchType = $type;
                $catchUri = $uri;
                $catchRequestInfo = $requestInfo;
                return $response;
            }));

        $request = new Request($client, $apiKey);
        $places = new Places($request);
        $response = $places->update($place, $id);

        $primitiveImages = [];
        foreach ($place->getImages() AS $image)
        {
            $primitiveImages[] = $image->toPrimitiveArray();
        }

        $this->assertInternalType('array', $catchRequestInfo['json']);
        $this->assertArrayHasKey('place', $catchRequestInfo['json']);
        $this->assertArrayHasKey('title', $catchRequestInfo['json']['place']);
        $this->assertArrayHasKey('description', $catchRequestInfo['json']['place']);
        $this->assertArrayHasKey('address', $catchRequestInfo['json']['place']);
        $this->assertArrayHasKey('lat', $catchRequestInfo['json']['place']);
        $this->assertArrayHasKey('lon', $catchRequestInfo['json']['place']);
        $this->assertArrayHasKey('categoryId', $catchRequestInfo['json']['place']);
        $this->assertArrayHasKey('images', $catchRequestInfo['json']['place']);
        $this->assertArrayHasKey('attachmentUrl', $catchRequestInfo['json']['place']);
        $this->assertArrayHasKey('isVisible', $catchRequestInfo['json']['place']);
        $this->assertArrayHasKey('approveState', $catchRequestInfo['json']['place']);
        $this->assertEquals($place->getTitle(), $catchRequestInfo['json']['place']['title']);
        $this->assertEquals($place->getDescription(), $catchRequestInfo['json']['place']['description']);
        $this->assertEquals($place->getAddress(), $catchRequestInfo['json']['place']['address']);
        $this->assertEquals($place->getLat(), $catchRequestInfo['json']['place']['lat']);
        $this->assertEquals($place->getLon(), $catchRequestInfo['json']['place']['lon']);
        $this->assertEquals($place->getCategoryId(), $catchRequestInfo['json']['place']['categoryId']);
        $this->assertEquals($primitiveImages, $catchRequestInfo['json']['place']['images']);
        $this->assertEquals($place->getAttachmentUrl(), $catchRequestInfo['json']['place']['attachmentUrl']);
        $this->assertEquals($place->getIsVisible(), $catchRequestInfo['json']['place']['isVisible']);
        $this->assertEquals($place->getApproveState(), $catchRequestInfo['json']['place']['approveState']);
        $this->assertEquals('POST', $catchType);
        $this->assertEquals('Basic '.$apiKey, $catchRequestInfo['headers']['Authorization']);
        $this->assertEquals('/api/import/places', $catchUri);
        $this->assertEquals(RequestActionCodeEnum::ACTION_CODE_UPDATE, $catchRequestInfo['json']['action']['code']);
        $this->assertEquals((is_null($id) ? $place->getId() : $id), $catchRequestInfo['json']['action']['id']);
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @test
     * @dataProvider provideValidDeleteConstructorParameters
     * @param IPlace|null $place
     * @param int|null $id
     */
    public function deleteRequestShouldBeGoodTest(IPlace $place = null, int $id = null)
    {
        $apiKey = $this->getTestApiKey();

        $catchType = null;
        $catchUri = null;
        $catchRequestInfo = null;
        $responseData = $this->getFakeResponseData();
        $responseDataString = json_encode($responseData);

        $response = $this->getResponseMockWithBody($responseDataString);

        $client = $this->getClientMock();
        $client->expects($this->once())
            ->method('request')
            ->will($this->returnCallback(function ($type, $uri, $requestInfo) use (&$catchRequestInfo, &$catchType, &$catchUri, $response) {
                $catchType = $type;
                $catchUri = $uri;
                $catchRequestInfo = $requestInfo;
                return $response;
            }));

        $request = new Request($client, $apiKey);
        $places = new Places($request);
        $response = $places->delete($place, $id);

        $this->assertInternalType('array', $catchRequestInfo['json']);
        $this->assertEquals('POST', $catchType);
        $this->assertEquals('Basic '.$apiKey, $catchRequestInfo['headers']['Authorization']);
        $this->assertEquals('/api/import/places', $catchUri);
        $this->assertEquals(RequestActionCodeEnum::ACTION_CODE_DELETE, $catchRequestInfo['json']['action']['code']);
        $this->assertEquals((is_null($id) ? $place->getId() : $id), $catchRequestInfo['json']['action']['id']);
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @test
     * @dataProvider provideInvalidDeleteConstructorParameters
     * @expectedException Salamek\MojeOlomouc\Exception\InvalidArgumentException
     * @param IPlace|null $place
     * @param int|null $id
     */
    public function deleteRequestShouldFailTest(IPlace $place = null, int $id = null)
    {
        $apiKey = $this->getTestApiKey();

        $client = $this->getClientMock();

        $request = new Request($client, $apiKey);
        $places = new Places($request);
        $places->delete($place, $id);
    }

    /**
     * @return array
     */
    public function provideInvalidDeleteConstructorParameters(): array
    {
        return [
            [null, null],
            [new Place(
                'title-'.mt_rand(),
                'description-'.mt_rand(),
                'address-'.mt_rand(),
                '12.'.mt_rand(),
                '-12.'.mt_rand(),
                mt_rand(),
                [new EntityImage('url-'.mt_rand())],
                'attachmentUrl-'.mt_rand(),
                false,
                PlaceApproveStateEnum::APPROVED,
                null
            ), null]
        ];
    }

    /**
     * @return array
     */
    public function provideValidDeleteConstructorParameters(): array
    {
        return [
            [null, mt_rand()],
            [new Place(
                'title-'.mt_rand(),
                'description-'.mt_rand(),
                'address-'.mt_rand(),
                '12.'.mt_rand(),
                '-12.'.mt_rand(),
                mt_rand(),
                [new EntityImage('url-'.mt_rand())],
                'attachmentUrl-'.mt_rand(),
                false,
                PlaceApproveStateEnum::APPROVED,
                mt_rand()
            ), null]
        ];
    }

    /**
     * @return array
     */
    public function provideUpdateConstructorParameters(): array
    {
        return [
            [new Place(
                'title-'.mt_rand(),
                'description-'.mt_rand(),
                'address-'.mt_rand(),
                '12.'.mt_rand(),
                '-12.'.mt_rand(),
                mt_rand()
            ), mt_rand()],
            [new Place(
                'title-'.mt_rand(),
                'description-'.mt_rand(),
                'address-'.mt_rand(),
                '12.'.mt_rand(),
                '-12.'.mt_rand(),
                mt_rand(),
                [new EntityImage('url-'.mt_rand())],
                'attachmentUrl-'.mt_rand(),
                false,
                PlaceApproveStateEnum::APPROVED,
                mt_rand()
            )],
        ];
    }

    /**
     * @return array
     */
    public function provideCreateConstructorParameters(): array
    {
        return [
            [new Place(
                'title-'.mt_rand(),
                'description-'.mt_rand(),
                'address-'.mt_rand(),
                '12.'.mt_rand(),
                '-12.'.mt_rand(),
                mt_rand()
            )],
            [new Place(
                'title-'.mt_rand(),
                'description-'.mt_rand(),
                'address-'.mt_rand(),
                '12.'.mt_rand(),
                '-12.'.mt_rand(),
                mt_rand(),
                [new EntityImage('url-'.mt_rand())],
                'attachmentUrl-'.mt_rand(),
                false,
                PlaceApproveStateEnum::APPROVED,
                mt_rand()
            )],
        ];
    }

    /**
     * @return array
     */
    public function provideGetAllConstructorParameters(): array
    {
        return [
            [
                null,
                false,
                true,
                true,
                false,
            ],
            [
                new \DateTime(),
                false,
                true,
                true,
                false,
            ],
            [
                null,
                true,
                true,
                true,
                false,
            ],
            [
                null,
                false,
                false,
                true,
                false,
            ],
            [
                null,
                false,
                true,
                false,
                false,
            ],
            [
                null,
                false,
                true,
                true,
                true,
            ]
        ];
    }
}