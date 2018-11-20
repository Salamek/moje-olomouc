<?php

declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc;

use Salamek\MojeOlomouc\Enum\DateTime;
use Salamek\MojeOlomouc\Enum\PlaceApproveStateEnum;
use Salamek\MojeOlomouc\Enum\PlaceSourceEnum;
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
     * @param \DateTimeInterface|null $from
     * @param bool $deleted
     * @param bool $invisible
     * @param bool $withExtraFields
     * @param string $source
     * @param bool $own
     */
    public function getAllShouldBeGoodTest(
        \DateTimeInterface $from = null,
        bool $deleted = false,
        bool $invisible = false,
        bool $withExtraFields = false,
        string $source = PlaceSourceEnum::PUBLISHED,
        bool $own = false
    ): void
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
            $from,
            $deleted,
            $invisible,
            $withExtraFields,
            $source,
            $own
        );

        $this->assertInternalType('array', $catchRequestInfo['query']);
        $this->assertArrayHasKey('from', $catchRequestInfo['query']);
        $this->assertArrayHasKey('deleted', $catchRequestInfo['query']);
        $this->assertArrayHasKey('invisible', $catchRequestInfo['query']);
        $this->assertArrayHasKey('withExtraFields', $catchRequestInfo['query']);
        $this->assertArrayHasKey('source', $catchRequestInfo['query']);
        $this->assertArrayHasKey('own', $catchRequestInfo['query']);
        $this->assertEquals(($from ? $from->format(DateTime::A_ISO8601) : null), $catchRequestInfo['query']['from']);
        $this->assertEquals($this->boolToString($deleted), $catchRequestInfo['query']['deleted']);
        $this->assertEquals($this->boolToString($invisible), $catchRequestInfo['query']['invisible']);
        $this->assertEquals($this->boolToString($withExtraFields), $catchRequestInfo['query']['withExtraFields']);
        $this->assertEquals($source, $catchRequestInfo['query']['source']);
        $this->assertEquals($this->boolToString($own), $catchRequestInfo['query']['own']);
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
                floatval('12.'.mt_rand()),
                floatval('-12.'.mt_rand()),
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
                floatval('12.'.mt_rand()),
                floatval('-12.'.mt_rand()),
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
                floatval('12.'.mt_rand()),
                floatval('-12.'.mt_rand()),
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
                floatval('12.'.mt_rand()),
                floatval('-12.'.mt_rand()),
                mt_rand()
            )],
            [new Place(
                'title-'.mt_rand(),
                'description-'.mt_rand(),
                'address-'.mt_rand(),
                floatval('12.'.mt_rand()),
                floatval('-12.'.mt_rand()),
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