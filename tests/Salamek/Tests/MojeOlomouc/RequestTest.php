<?php
declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc;


use Salamek\MojeOlomouc\Enum\RequestActionCodeEnum;
use Salamek\MojeOlomouc\Request;
use Salamek\MojeOlomouc\Response;

class RequestTest extends BaseTest
{
    /**
     * @test
     */
    public function createShouldBeGoodTest()
    {
        $apiKey = $this->getTestApiKey();
        
        $client = $this->getClientMock();

        new Request($client, $apiKey);

        $this->assertEquals(true, true);
    }

    /**
     * @test
     */
    public function getRequestShouldBeGoodTest()
    {
        $apiKey = $this->getTestApiKey();

        $uri = 'uri-'.mt_rand();
        $arguments = [
            'a' => 'b',
            'foo' => 'bar'
        ];

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
        $response = $request->get($uri, $arguments);

        $this->assertInternalType('array', $catchRequestInfo['query']);
        $this->assertArrayHasKey('a', $catchRequestInfo['query']);
        $this->assertArrayHasKey('foo', $catchRequestInfo['query']);
        $this->assertEquals('b', $catchRequestInfo['query']['a']);
        $this->assertEquals('bar', $catchRequestInfo['query']['foo']);
        $this->assertEquals('GET', $catchType);
        $this->assertEquals('Basic '.$apiKey, $catchRequestInfo['headers']['Authorization']);
        $this->assertEquals($uri, $catchUri);
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @test
     */
    public function createRequestShouldBeGoodTest()
    {
        $apiKey = $this->getTestApiKey();

        $uri = 'uri-'.mt_rand();
        $arguments = [
            'test' => [
                'a' => 'b',
                'foo' => 'bar'
            ]
        ];

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
        $response = $request->create($uri, $arguments);

        $primitivePayloadItem = $catchRequestInfo['json'][0];

        $this->assertInternalType('array', $primitivePayloadItem);
        $this->assertArrayHasKey('test', $primitivePayloadItem);
        $this->assertArrayHasKey('action', $primitivePayloadItem);

        $primitiveTest = $primitivePayloadItem['test'];
        $primitiveAction = $primitivePayloadItem['action'];

        $this->assertInternalType('array', $primitiveTest);
        $this->assertInternalType('array', $primitiveAction);
        $this->assertArrayHasKey('a', $primitiveTest);
        $this->assertArrayHasKey('foo', $primitiveTest);
        $this->assertEquals('b', $primitiveTest['a']);
        $this->assertEquals('bar', $primitiveTest['foo']);
        $this->assertEquals('POST', $catchType);
        $this->assertEquals('Basic '.$apiKey, $catchRequestInfo['headers']['Authorization']);
        $this->assertEquals($uri, $catchUri);
        $this->assertEquals(RequestActionCodeEnum::ACTION_CODE_CREATE, $primitiveAction['code']);
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @test
     */
    public function updateRequestShouldBeGoodTest()
    {
        $apiKey = $this->getTestApiKey();

        $id = mt_rand();
        $uri = 'uri-'.mt_rand();
        $arguments = [
            'test' => [
                'a' => 'b',
                'foo' => 'bar'
            ]
        ];

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
        $response = $request->update($uri, $id, $arguments);

        $primitivePayloadItem = $catchRequestInfo['json'][0];

        $this->assertInternalType('array', $primitivePayloadItem);
        $this->assertArrayHasKey('test', $primitivePayloadItem);
        $this->assertArrayHasKey('action', $primitivePayloadItem);

        $primitiveTest = $primitivePayloadItem['test'];
        $primitiveAction = $primitivePayloadItem['action'];

        $this->assertInternalType('array', $primitiveTest);
        $this->assertInternalType('array', $primitiveAction);
        $this->assertArrayHasKey('a', $primitiveTest);
        $this->assertArrayHasKey('foo', $primitiveTest);
        $this->assertEquals('b', $primitiveTest['a']);
        $this->assertEquals('bar', $primitiveTest['foo']);
        $this->assertEquals('POST', $catchType);
        $this->assertEquals('Basic '.$apiKey, $catchRequestInfo['headers']['Authorization']);
        $this->assertEquals($uri, $catchUri);
        $this->assertEquals(RequestActionCodeEnum::ACTION_CODE_UPDATE, $primitiveAction['code']);
        $this->assertEquals($id, $primitiveAction['id']);
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @test
     */
    public function deleteRequestShouldBeGoodTest()
    {
        $apiKey = $this->getTestApiKey();

        $id = mt_rand();
        $uri = 'uri-'.mt_rand();

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
        $response = $request->delete($uri, $id);

        $primitivePayloadItem = $catchRequestInfo['json'][0];
        $this->assertInternalType('array', $primitivePayloadItem);
        $this->assertArrayHasKey('action', $primitivePayloadItem);

        $primitiveAction = $primitivePayloadItem['action'];

        $this->assertInternalType('array', $primitiveAction);

        $this->assertEquals('POST', $catchType);
        $this->assertEquals('Basic '.$apiKey, $catchRequestInfo['headers']['Authorization']);
        $this->assertEquals($uri, $catchUri);
        $this->assertEquals(RequestActionCodeEnum::ACTION_CODE_DELETE, $primitiveAction['code']);
        $this->assertEquals($id, $primitiveAction['id']);
        $this->assertInstanceOf(Response::class, $response);
    }
}