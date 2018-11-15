<?php

declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc;

use Salamek\MojeOlomouc\Enum\DateTime;
use Salamek\MojeOlomouc\Enum\ImportantMessageSeverityEnum;
use Salamek\MojeOlomouc\Enum\ImportantMessageTypeEnum;
use Salamek\MojeOlomouc\Enum\RequestActionCodeEnum;
use Salamek\MojeOlomouc\Model\ImportantMessage;
use Salamek\MojeOlomouc\Model\EntityImage;
use Salamek\MojeOlomouc\Model\IImportantMessage;
use Salamek\MojeOlomouc\Operation\ImportantMessages;
use Salamek\MojeOlomouc\Request;
use Salamek\MojeOlomouc\Response;


class ImportantMessagesTest extends BaseTest
{
    /**
     * @test
     */
    public function getAllShouldBeGoodTest(): void
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

        $places = new ImportantMessages($request);
        $response = $places->getAll();

        $this->assertEquals('GET', $catchType);
        $this->assertEquals('Basic '.$apiKey, $catchRequestInfo['headers']['Authorization']);
        $this->assertEquals('/api/export/important-messages', $catchUri);
        $this->assertInstanceOf(Response::class, $response);
    }


    /**
     * @test
     * @dataProvider provideCreateConstructorParameters
     * @param IImportantMessage $importantMessage
     */
    public function createShouldBeGoodTest(IImportantMessage $importantMessage)
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
        $importantMessages = new ImportantMessages($request);
        $response = $importantMessages->create([$importantMessage]);

        $primitivePayloadItem = $catchRequestInfo['json'][0];

        $this->assertInternalType('array', $primitivePayloadItem);
        $this->assertArrayHasKey('importantMessage', $primitivePayloadItem);
        $this->assertArrayHasKey('action', $primitivePayloadItem);

        $primitiveImportantMessage = $primitivePayloadItem['importantMessage'];
        $primitiveAction = $primitivePayloadItem['action'];

        $this->assertInternalType('array', $primitiveImportantMessage);
        $this->assertInternalType('array', $primitiveAction);
        $this->assertArrayHasKey('text', $primitiveImportantMessage);
        $this->assertArrayHasKey('dateTimeAt', $primitiveImportantMessage);
        $this->assertArrayHasKey('type', $primitiveImportantMessage);
        $this->assertArrayHasKey('severity', $primitiveImportantMessage);
        if (!is_null($importantMessage->getExpireAt())) $this->assertArrayHasKey('expireAt', $primitiveImportantMessage);
        if (!is_null($importantMessage->getIsVisible())) $this->assertArrayHasKey('isVisible', $primitiveImportantMessage);

        $this->assertEquals($importantMessage->getText(), $primitiveImportantMessage['text']);
        $this->assertEquals($importantMessage->getDateTimeAt()->format(DateTime::NOT_A_ISO8601), $primitiveImportantMessage['dateTimeAt']);
        $this->assertEquals($importantMessage->getType(), $primitiveImportantMessage['type']);
        $this->assertEquals($importantMessage->getSeverity(), $primitiveImportantMessage['severity']);
        if (!is_null($importantMessage->getExpireAt())) $this->assertEquals($importantMessage->getExpireAt()->format(DateTime::NOT_A_ISO8601), $primitiveImportantMessage['expireAt']);
        if (!is_null($importantMessage->getIsVisible())) $this->assertEquals($importantMessage->getIsVisible(), $primitiveImportantMessage['isVisible']);
        $this->assertEquals('POST', $catchType);
        $this->assertEquals('Basic '.$apiKey, $catchRequestInfo['headers']['Authorization']);
        $this->assertEquals('/api/import/important-messages', $catchUri);
        $this->assertEquals(RequestActionCodeEnum::ACTION_CODE_CREATE, $primitiveAction['code']);
        $this->assertEquals($importantMessage->getId(), $primitiveAction['id']);
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @test
     * @dataProvider provideUpdateConstructorParameters
     * @param IImportantMessage $importantMessage
     */
    public function updateShouldBeGoodTest(IImportantMessage $importantMessage)
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
        $importantMessages = new ImportantMessages($request);
        $response = $importantMessages->update([$importantMessage]);

        $primitivePayloadItem = $catchRequestInfo['json'][0];

        $this->assertInternalType('array', $primitivePayloadItem);
        $this->assertArrayHasKey('importantMessage', $primitivePayloadItem);
        $this->assertArrayHasKey('action', $primitivePayloadItem);

        $primitiveImportantMessage = $primitivePayloadItem['importantMessage'];
        $primitiveAction = $primitivePayloadItem['action'];

        $this->assertInternalType('array', $primitiveImportantMessage);
        $this->assertInternalType('array', $primitiveAction);
        $this->assertArrayHasKey('text', $primitiveImportantMessage);
        $this->assertArrayHasKey('dateTimeAt', $primitiveImportantMessage);
        $this->assertArrayHasKey('type', $primitiveImportantMessage);
        $this->assertArrayHasKey('severity', $primitiveImportantMessage);
        if (!is_null($importantMessage->getExpireAt())) $this->assertArrayHasKey('expireAt', $primitiveImportantMessage);
        if (!is_null($importantMessage->getIsVisible())) $this->assertArrayHasKey('isVisible', $primitiveImportantMessage);

        $this->assertEquals($importantMessage->getText(), $primitiveImportantMessage['text']);
        $this->assertEquals($importantMessage->getDateTimeAt()->format(DateTime::NOT_A_ISO8601), $primitiveImportantMessage['dateTimeAt']);
        $this->assertEquals($importantMessage->getType(), $primitiveImportantMessage['type']);
        $this->assertEquals($importantMessage->getSeverity(), $primitiveImportantMessage['severity']);
        if (!is_null($importantMessage->getExpireAt())) $this->assertEquals($importantMessage->getExpireAt()->format(DateTime::NOT_A_ISO8601), $primitiveImportantMessage['expireAt']);
        if (!is_null($importantMessage->getIsVisible())) $this->assertEquals($importantMessage->getIsVisible(), $primitiveImportantMessage['isVisible']);
        $this->assertEquals('POST', $catchType);
        $this->assertEquals('Basic '.$apiKey, $catchRequestInfo['headers']['Authorization']);
        $this->assertEquals('/api/import/important-messages', $catchUri);
        $this->assertEquals(RequestActionCodeEnum::ACTION_CODE_UPDATE, $primitiveAction['code']);
        $this->assertEquals($importantMessage->getId(), $primitiveAction['id']);
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @test
     * @dataProvider provideValidDeleteConstructorParameters
     * @param IImportantMessage $importantMessage
     */
    public function deleteRequestShouldBeGoodTest(IImportantMessage $importantMessage = null)
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
        $importantMessages = new ImportantMessages($request);
        $response = $importantMessages->delete([$importantMessage]);

        $primitivePayloadItem = $catchRequestInfo['json'][0];
        $this->assertInternalType('array', $primitivePayloadItem);
        $this->assertArrayHasKey('action', $primitivePayloadItem);

        $primitiveAction = $primitivePayloadItem['action'];

        $this->assertInternalType('array', $primitiveAction);

        $this->assertEquals('POST', $catchType);
        $this->assertEquals('Basic '.$apiKey, $catchRequestInfo['headers']['Authorization']);
        $this->assertEquals('/api/import/important-messages', $catchUri);
        $this->assertEquals(RequestActionCodeEnum::ACTION_CODE_DELETE, $primitiveAction['code']);
        $this->assertEquals($importantMessage->getId(), $primitiveAction['id']);
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @test
     * @dataProvider provideInvalidDeleteConstructorParameters
     * @expectedException Salamek\MojeOlomouc\Exception\InvalidArgumentException
     * @param IImportantMessage $importantMessage
     */
    public function deleteRequestShouldFailTest(IImportantMessage $importantMessage)
    {
        $apiKey = $this->getTestApiKey();

        $client = $this->getClientMock();

        $request = new Request($client, $apiKey);
        $importantMessages = new ImportantMessages($request);
        $importantMessages->delete([$importantMessage]);
    }

    /**
     * @return array
     */
    public function provideInvalidDeleteConstructorParameters(): array
    {
        return [
            [new ImportantMessage(
                'text-'.mt_rand(),
                $this->getDateTime(),
                ImportantMessageTypeEnum::TRAFFIC_SITUATION,
                ImportantMessageSeverityEnum::WARNING,
                $this->getDateTime(),
                false,
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
            [new ImportantMessage(
                'text-'.mt_rand(),
                $this->getDateTime(),
                ImportantMessageTypeEnum::TRAFFIC_SITUATION,
                ImportantMessageSeverityEnum::WARNING,
                $this->getDateTime(),
                false,
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
            [new ImportantMessage(
                'text-'.mt_rand(),
                $this->getDateTime(),
                ImportantMessageTypeEnum::TRAFFIC_SITUATION,
                ImportantMessageSeverityEnum::WARNING,
                $this->getDateTime(),
                false,
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
            [new ImportantMessage(
                'text-'.mt_rand(),
                $this->getDateTime(),
                ImportantMessageTypeEnum::TRAFFIC_SITUATION,
                ImportantMessageSeverityEnum::WARNING
            )],
            [new ImportantMessage(
                'text-'.mt_rand(),
                $this->getDateTime(),
                ImportantMessageTypeEnum::TRAFFIC_SITUATION,
                ImportantMessageSeverityEnum::WARNING,
                $this->getDateTime(),
                false,
                mt_rand()
            )],
        ];
    }

}