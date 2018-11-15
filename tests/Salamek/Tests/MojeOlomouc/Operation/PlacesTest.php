<?php

declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc;

use Salamek\MojeOlomouc\Enum\DateTime;
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
        $this->assertEquals(($fromUpdatedAt ? $fromUpdatedAt->format(DateTime::NOT_A_ISO8601) : null), $catchRequestInfo['query']['fromUpdatedAt']);
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
        $response = $places->create([$place]);

        $primitiveImages = [];
        foreach ($place->getImages() AS $image)
        {
            $primitiveImages[] = $image->toPrimitiveArray();
        }

        $primitivePayloadItem = $catchRequestInfo['json'][0];

        $this->assertInternalType('array', $primitivePayloadItem);
        $this->assertArrayHasKey('place', $primitivePayloadItem);
        $this->assertArrayHasKey('action', $primitivePayloadItem);

        $primitivePlace = $primitivePayloadItem['place'];
        $primitiveAction = $primitivePayloadItem['action'];

        $this->assertInternalType('array', $primitivePlace);
        $this->assertInternalType('array', $primitiveAction);
        $this->assertArrayHasKey('title', $primitivePlace);
        $this->assertArrayHasKey('description', $primitivePlace);
        $this->assertArrayHasKey('address', $primitivePlace);
        $this->assertArrayHasKey('lat', $primitivePlace);
        $this->assertArrayHasKey('lon', $primitivePlace);
        $this->assertArrayHasKey('categoryId', $primitivePlace);
        $this->assertArrayHasKey('images', $primitivePlace);
        if (!is_null($place->getAttachmentUrl())) $this->assertArrayHasKey('attachmentUrl', $primitivePlace);
        if (!is_null($place->getIsVisible())) $this->assertArrayHasKey('isVisible', $primitivePlace);
        if (!is_null($place->getApproveState())) $this->assertArrayHasKey('approveState', $primitivePlace);
        $this->assertEquals($place->getTitle(), $primitivePlace['title']);
        $this->assertEquals($place->getDescription(), $primitivePlace['description']);
        $this->assertEquals($place->getAddress(), $primitivePlace['address']);
        $this->assertEquals($place->getLat(), $primitivePlace['lat']);
        $this->assertEquals($place->getLon(), $primitivePlace['lon']);
        $this->assertEquals($place->getCategoryId(), $primitivePlace['categoryId']);
        $this->assertEquals($primitiveImages, $primitivePlace['images']);
        if (!is_null($place->getAttachmentUrl())) $this->assertEquals($place->getAttachmentUrl(), $primitivePlace['attachmentUrl']);
        if (!is_null($place->getIsVisible())) $this->assertEquals($place->getIsVisible(), $primitivePlace['isVisible']);
        if (!is_null($place->getApproveState())) $this->assertEquals($place->getApproveState(), $primitivePlace['approveState']);
        $this->assertEquals('POST', $catchType);
        $this->assertEquals('Basic '.$apiKey, $catchRequestInfo['headers']['Authorization']);
        $this->assertEquals('/api/import/places', $catchUri);
        $this->assertEquals(RequestActionCodeEnum::ACTION_CODE_CREATE, $primitiveAction['code']);
        $this->assertEquals($place->getId(), $primitiveAction['id']);
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @test
     * @dataProvider provideUpdateConstructorParameters
     * @param IPlace $place
     */
    public function updateShouldBeGoodTest(IPlace $place)
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
        $response = $places->update([$place]);

        $primitiveImages = [];
        foreach ($place->getImages() AS $image)
        {
            $primitiveImages[] = $image->toPrimitiveArray();
        }

        $primitivePayloadItem = $catchRequestInfo['json'][0];

        $this->assertInternalType('array', $primitivePayloadItem);
        $this->assertArrayHasKey('place', $primitivePayloadItem);
        $this->assertArrayHasKey('action', $primitivePayloadItem);

        $primitivePlace = $primitivePayloadItem['place'];
        $primitiveAction = $primitivePayloadItem['action'];

        $this->assertInternalType('array', $primitivePlace);
        $this->assertInternalType('array', $primitiveAction);
        $this->assertArrayHasKey('title', $primitivePlace);
        $this->assertArrayHasKey('description', $primitivePlace);
        $this->assertArrayHasKey('address', $primitivePlace);
        $this->assertArrayHasKey('lat', $primitivePlace);
        $this->assertArrayHasKey('lon', $primitivePlace);
        $this->assertArrayHasKey('categoryId', $primitivePlace);
        $this->assertArrayHasKey('images', $primitivePlace);
        if (!is_null($place->getAttachmentUrl())) $this->assertArrayHasKey('attachmentUrl', $primitivePlace);
        if (!is_null($place->getIsVisible())) $this->assertArrayHasKey('isVisible', $primitivePlace);
        if (!is_null($place->getApproveState())) $this->assertArrayHasKey('approveState', $primitivePlace);
        $this->assertEquals($place->getTitle(), $primitivePlace['title']);
        $this->assertEquals($place->getDescription(), $primitivePlace['description']);
        $this->assertEquals($place->getAddress(), $primitivePlace['address']);
        $this->assertEquals($place->getLat(), $primitivePlace['lat']);
        $this->assertEquals($place->getLon(), $primitivePlace['lon']);
        $this->assertEquals($place->getCategoryId(), $primitivePlace['categoryId']);
        $this->assertEquals($primitiveImages, $primitivePlace['images']);
        if (!is_null($place->getAttachmentUrl())) $this->assertEquals($place->getAttachmentUrl(), $primitivePlace['attachmentUrl']);
        if (!is_null($place->getIsVisible())) $this->assertEquals($place->getIsVisible(), $primitivePlace['isVisible']);
        if (!is_null($place->getApproveState())) $this->assertEquals($place->getApproveState(), $primitivePlace['approveState']);
        $this->assertEquals('POST', $catchType);
        $this->assertEquals('Basic '.$apiKey, $catchRequestInfo['headers']['Authorization']);
        $this->assertEquals('/api/import/places', $catchUri);
        $this->assertEquals(RequestActionCodeEnum::ACTION_CODE_UPDATE, $primitiveAction['code']);
        $this->assertEquals($place->getId(), $primitiveAction['id']);
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @test
     * @dataProvider provideValidDeleteConstructorParameters
     * @param IPlace $place
     */
    public function deleteRequestShouldBeGoodTest(IPlace $place)
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
        $response = $places->delete([$place]);

        $primitivePayloadItem = $catchRequestInfo['json'][0];
        $this->assertInternalType('array', $primitivePayloadItem);
        $this->assertArrayHasKey('action', $primitivePayloadItem);

        $primitiveAction = $primitivePayloadItem['action'];

        $this->assertInternalType('array', $primitiveAction);
        $this->assertEquals('POST', $catchType);
        $this->assertEquals('Basic '.$apiKey, $catchRequestInfo['headers']['Authorization']);
        $this->assertEquals('/api/import/places', $catchUri);
        $this->assertEquals(RequestActionCodeEnum::ACTION_CODE_DELETE, $primitiveAction['code']);
        $this->assertEquals($place->getId(), $primitiveAction['id']);
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @test
     * @dataProvider provideInvalidDeleteConstructorParameters
     * @expectedException Salamek\MojeOlomouc\Exception\InvalidArgumentException
     * @param IPlace $place
     */
    public function deleteRequestShouldFailTest(IPlace $place)
    {
        $apiKey = $this->getTestApiKey();

        $client = $this->getClientMock();

        $request = new Request($client, $apiKey);
        $places = new Places($request);
        $places->delete([$place]);
    }

    /**
     * @return array
     */
    public function provideInvalidDeleteConstructorParameters(): array
    {
        return [
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
            )]
        ];
    }

    /**
     * @return array
     */
    public function provideValidDeleteConstructorParameters(): array
    {
        return [
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
            )]
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
                $this->getDateTime(),
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