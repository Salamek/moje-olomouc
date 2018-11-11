<?php

declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc;

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
        $response = $importantMessages->create($importantMessage);

        $this->assertInternalType('array', $catchRequestInfo['json']);
        $this->assertArrayHasKey('importantMessage', $catchRequestInfo['json']);
        $this->assertArrayHasKey('text', $catchRequestInfo['json']['importantMessage']);
        $this->assertArrayHasKey('dateTimeAt', $catchRequestInfo['json']['importantMessage']);
        $this->assertArrayHasKey('expireAt', $catchRequestInfo['json']['importantMessage']);
        $this->assertArrayHasKey('type', $catchRequestInfo['json']['importantMessage']);
        $this->assertArrayHasKey('severity', $catchRequestInfo['json']['importantMessage']);
        $this->assertArrayHasKey('isVisible', $catchRequestInfo['json']['importantMessage']);

        $this->assertEquals($importantMessage->getText(), $catchRequestInfo['json']['importantMessage']['text']);
        $this->assertEquals($importantMessage->getDateTimeAt()->format(\DateTime::ISO8601), $catchRequestInfo['json']['importantMessage']['dateTimeAt']);
        $this->assertEquals($importantMessage->getExpireAt()->format(\DateTime::ISO8601), $catchRequestInfo['json']['importantMessage']['expireAt']);
        $this->assertEquals($importantMessage->getType(), $catchRequestInfo['json']['importantMessage']['type']);
        $this->assertEquals($importantMessage->getSeverity(), $catchRequestInfo['json']['importantMessage']['severity']);
        $this->assertEquals($importantMessage->getIsVisible(), $catchRequestInfo['json']['importantMessage']['isVisible']);
        $this->assertEquals('POST', $catchType);
        $this->assertEquals('Basic '.$apiKey, $catchRequestInfo['headers']['Authorization']);
        $this->assertEquals('/api/import/important-messages', $catchUri);
        $this->assertEquals(RequestActionCodeEnum::ACTION_CODE_CREATE, $catchRequestInfo['json']['action']['code']);
        $this->assertEquals(null, $catchRequestInfo['json']['action']['id']);
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @test
     * @dataProvider provideUpdateConstructorParameters
     * @param IImportantMessage $importantMessage
     * @param int|null $id
     */
    public function updateShouldBeGoodTest(IImportantMessage $importantMessage, int $id = null)
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
        $response = $importantMessages->update($importantMessage, $id);

        $this->assertInternalType('array', $catchRequestInfo['json']);
        $this->assertArrayHasKey('importantMessage', $catchRequestInfo['json']);
        $this->assertArrayHasKey('text', $catchRequestInfo['json']['importantMessage']);
        $this->assertArrayHasKey('dateTimeAt', $catchRequestInfo['json']['importantMessage']);
        $this->assertArrayHasKey('expireAt', $catchRequestInfo['json']['importantMessage']);
        $this->assertArrayHasKey('type', $catchRequestInfo['json']['importantMessage']);
        $this->assertArrayHasKey('severity', $catchRequestInfo['json']['importantMessage']);
        $this->assertArrayHasKey('isVisible', $catchRequestInfo['json']['importantMessage']);

        $this->assertEquals($importantMessage->getText(), $catchRequestInfo['json']['importantMessage']['text']);
        $this->assertEquals($importantMessage->getDateTimeAt()->format(\DateTime::ISO8601), $catchRequestInfo['json']['importantMessage']['dateTimeAt']);
        $this->assertEquals($importantMessage->getExpireAt()->format(\DateTime::ISO8601), $catchRequestInfo['json']['importantMessage']['expireAt']);
        $this->assertEquals($importantMessage->getType(), $catchRequestInfo['json']['importantMessage']['type']);
        $this->assertEquals($importantMessage->getSeverity(), $catchRequestInfo['json']['importantMessage']['severity']);
        $this->assertEquals($importantMessage->getIsVisible(), $catchRequestInfo['json']['importantMessage']['isVisible']);
        $this->assertEquals('POST', $catchType);
        $this->assertEquals('Basic '.$apiKey, $catchRequestInfo['headers']['Authorization']);
        $this->assertEquals('/api/import/important-messages', $catchUri);
        $this->assertEquals(RequestActionCodeEnum::ACTION_CODE_UPDATE, $catchRequestInfo['json']['action']['code']);
        $this->assertEquals((is_null($id) ? $importantMessage->getId() : $id), $catchRequestInfo['json']['action']['id']);
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @test
     * @dataProvider provideValidDeleteConstructorParameters
     * @param IImportantMessage|null $importantMessage
     * @param int|null $id
     */
    public function deleteRequestShouldBeGoodTest(IImportantMessage $importantMessage = null, int $id = null)
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
        $response = $importantMessages->delete($importantMessage, $id);

        $this->assertInternalType('array', $catchRequestInfo['json']);
        $this->assertEquals('POST', $catchType);
        $this->assertEquals('Basic '.$apiKey, $catchRequestInfo['headers']['Authorization']);
        $this->assertEquals('/api/import/important-messages', $catchUri);
        $this->assertEquals(RequestActionCodeEnum::ACTION_CODE_DELETE, $catchRequestInfo['json']['action']['code']);
        $this->assertEquals((is_null($id) ? $importantMessage->getId() : $id), $catchRequestInfo['json']['action']['id']);
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @test
     * @dataProvider provideInvalidDeleteConstructorParameters
     * @expectedException Salamek\MojeOlomouc\Exception\InvalidArgumentException
     * @param IImportantMessage|null $importantMessage
     * @param int|null $id
     */
    public function deleteRequestShouldFailTest(IImportantMessage $importantMessage = null, int $id = null)
    {
        $apiKey = $this->getTestApiKey();

        $client = $this->getClientMock();

        $request = new Request($client, $apiKey);
        $importantMessages = new ImportantMessages($request);
        $importantMessages->delete($importantMessage, $id);
    }

    /**
     * @return array
     */
    public function provideInvalidDeleteConstructorParameters(): array
    {
        return [
            [null, null],
            [new ImportantMessage(
                'text-'.mt_rand(),
                new \DateTime(),
                new \DateTime(),
                ImportantMessageTypeEnum::TRAFFIC_SITUATION,
                ImportantMessageSeverityEnum::WARNING,
                false,
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
            [new ImportantMessage(
                'text-'.mt_rand(),
                new \DateTime(),
                new \DateTime(),
                ImportantMessageTypeEnum::TRAFFIC_SITUATION,
                ImportantMessageSeverityEnum::WARNING,
                false,
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
            [new ImportantMessage(
                'text-'.mt_rand(),
                new \DateTime(),
                new \DateTime(),
                ImportantMessageTypeEnum::TRAFFIC_SITUATION,
                ImportantMessageSeverityEnum::WARNING,
                false,
                null
            ), mt_rand()],
            [new ImportantMessage(
                'text-'.mt_rand(),
                new \DateTime(),
                new \DateTime(),
                ImportantMessageTypeEnum::TRAFFIC_SITUATION,
                ImportantMessageSeverityEnum::WARNING,
                false,
                mt_rand()
            ), null],
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
                new \DateTime(),
                new \DateTime()
            )],
            [new ImportantMessage(
                'text-'.mt_rand(),
                new \DateTime(),
                new \DateTime(),
                ImportantMessageTypeEnum::TRAFFIC_SITUATION,
                ImportantMessageSeverityEnum::WARNING,
                false,
                mt_rand()
            )],
        ];
    }

}