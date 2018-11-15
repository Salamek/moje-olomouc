<?php

declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc;

use Salamek\MojeOlomouc\Enum\ArticleCategoryConsumerFlagEnum;
use Salamek\MojeOlomouc\Enum\DateTime;
use Salamek\MojeOlomouc\Enum\RequestActionCodeEnum;
use Salamek\MojeOlomouc\Model\ArticleCategory;
use Salamek\MojeOlomouc\Model\IArticleCategory;
use Salamek\MojeOlomouc\Operation\ArticleCategories;
use Salamek\MojeOlomouc\Request;
use Salamek\MojeOlomouc\Response;

class ArticleCategoriesTest extends BaseTest
{
    /**
     * @test
     * @dataProvider provideGetAllConstructorParameters
     * @param \DateTimeInterface|null $fromUpdatedAt
     * @param bool $showDeleted
     * @param bool $onlyVisible
     * @param bool $extraFields
     */
    public function getAllShouldBeGoodTest(\DateTimeInterface $fromUpdatedAt = null, bool $showDeleted = false, bool $onlyVisible = true, bool $extraFields = false): void
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

        $articleCategories = new ArticleCategories($request);
        $response = $articleCategories->getAll(
            $fromUpdatedAt,
            $showDeleted,
            $onlyVisible,
            $extraFields
        );

        $this->assertInternalType('array', $catchRequestInfo['query']);
        $this->assertArrayHasKey('fromUpdatedAt', $catchRequestInfo['query']);
        $this->assertArrayHasKey('showDeleted', $catchRequestInfo['query']);
        $this->assertArrayHasKey('onlyVisible', $catchRequestInfo['query']);
        $this->assertArrayHasKey('extraFields', $catchRequestInfo['query']);
        $this->assertEquals(($fromUpdatedAt ? $fromUpdatedAt->format(DateTime::NOT_A_ISO8601) : null), $catchRequestInfo['query']['fromUpdatedAt']);
        $this->assertEquals($showDeleted, $catchRequestInfo['query']['showDeleted']);
        $this->assertEquals($onlyVisible, $catchRequestInfo['query']['onlyVisible']);
        $this->assertEquals($extraFields, $catchRequestInfo['query']['extraFields']);
        $this->assertEquals('GET', $catchType);
        $this->assertEquals('Basic '.$apiKey, $catchRequestInfo['headers']['Authorization']);
        $this->assertEquals('/api/export/article-categories', $catchUri);
        $this->assertInstanceOf(Response::class, $response);
    }


    /**
     * @test
     * @dataProvider provideCreateConstructorParameters
     * @param IArticleCategory $articleCategory
     */
    public function createShouldBeGoodTest(IArticleCategory $articleCategory)
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
        $articleCategories = new ArticleCategories($request);
        $response = $articleCategories->create([$articleCategory]);

        $primitivePayloadItem = $catchRequestInfo['json'][0];

        $this->assertInternalType('array', $primitivePayloadItem);
        $this->assertArrayHasKey('articleCategory', $primitivePayloadItem);
        $this->assertArrayHasKey('action', $primitivePayloadItem);

        $primitiveArticleCategory = $primitivePayloadItem['articleCategory'];
        $primitiveAction = $primitivePayloadItem['action'];

        $this->assertInternalType('array', $primitiveArticleCategory);
        $this->assertInternalType('array', $primitiveAction);
        $this->assertArrayHasKey('title', $primitiveArticleCategory);
        if (!is_null($articleCategory->getConsumerFlags())) $this->assertArrayHasKey('consumerFlags', $primitiveArticleCategory);
        if (!is_null($articleCategory->getIsImportant())) $this->assertArrayHasKey('isImportant', $primitiveArticleCategory);
        if (!is_null($articleCategory->getIsVisible())) $this->assertArrayHasKey('isVisible', $primitiveArticleCategory);

        $this->assertEquals($articleCategory->getTitle(), $primitiveArticleCategory['title']);
        if (!is_null($articleCategory->getConsumerFlags())) $this->assertEquals($articleCategory->getConsumerFlags(), $primitiveArticleCategory['consumerFlags']);
        if (!is_null($articleCategory->getIsImportant())) $this->assertEquals($articleCategory->getIsImportant(), $primitiveArticleCategory['isImportant']);
        if (!is_null($articleCategory->getIsVisible())) $this->assertEquals($articleCategory->getIsVisible(), $primitiveArticleCategory['isVisible']);

        $this->assertEquals('POST', $catchType);
        $this->assertEquals('Basic '.$apiKey, $catchRequestInfo['headers']['Authorization']);
        $this->assertEquals('/api/import/article-categories', $catchUri);
        $this->assertEquals(RequestActionCodeEnum::ACTION_CODE_CREATE, $primitiveAction['code']);
        $this->assertEquals($articleCategory->getId(), $primitiveAction['id']);
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @test
     * @dataProvider provideUpdateConstructorParameters
     * @param IArticleCategory $articleCategory
     */
    public function updateShouldBeGoodTest(IArticleCategory $articleCategory)
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
        $articleCategories = new ArticleCategories($request);
        $response = $articleCategories->update([$articleCategory]);

        $primitivePayloadItem = $catchRequestInfo['json'][0];

        $this->assertInternalType('array', $primitivePayloadItem);
        $this->assertArrayHasKey('articleCategory', $primitivePayloadItem);
        $this->assertArrayHasKey('action', $primitivePayloadItem);

        $primitiveArticleCategory = $primitivePayloadItem['articleCategory'];
        $primitiveAction = $primitivePayloadItem['action'];

        $this->assertInternalType('array', $primitiveAction);
        $this->assertInternalType('array', $primitiveArticleCategory);
        $this->assertArrayHasKey('title', $primitiveArticleCategory);
        if (!is_null($articleCategory->getConsumerFlags())) $this->assertArrayHasKey('consumerFlags', $primitiveArticleCategory);
        if (!is_null($articleCategory->getIsImportant())) $this->assertArrayHasKey('isImportant', $primitiveArticleCategory);
        if (!is_null($articleCategory->getIsVisible())) $this->assertArrayHasKey('isVisible', $primitiveArticleCategory);

        $this->assertEquals($articleCategory->getTitle(), $primitiveArticleCategory['title']);
        if (!is_null($articleCategory->getConsumerFlags())) $this->assertEquals($articleCategory->getConsumerFlags(), $primitiveArticleCategory['consumerFlags']);
        if (!is_null($articleCategory->getIsImportant())) $this->assertEquals($articleCategory->getIsImportant(), $primitiveArticleCategory['isImportant']);
        if (!is_null($articleCategory->getIsVisible())) $this->assertEquals($articleCategory->getIsVisible(), $primitiveArticleCategory['isVisible']);

        $this->assertEquals('POST', $catchType);
        $this->assertEquals('Basic '.$apiKey, $catchRequestInfo['headers']['Authorization']);
        $this->assertEquals('/api/import/article-categories', $catchUri);
        $this->assertEquals(RequestActionCodeEnum::ACTION_CODE_UPDATE, $primitiveAction['code']);
        $this->assertEquals($articleCategory->getId(), $primitiveAction['id']);
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @test
     * @dataProvider provideValidDeleteConstructorParameters
     * @param IArticleCategory|null $articleCategory
     */
    public function deleteRequestShouldBeGoodTest(IArticleCategory $articleCategory = null)
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
        $articleCategories = new ArticleCategories($request);
        $response = $articleCategories->delete([$articleCategory]);

        $primitivePayloadItem = $catchRequestInfo['json'][0];

        $this->assertInternalType('array', $primitivePayloadItem);
        $this->assertArrayHasKey('action', $primitivePayloadItem);

        $primitiveAction = $primitivePayloadItem['action'];

        $this->assertInternalType('array', $primitiveAction);

        $this->assertEquals('POST', $catchType);
        $this->assertEquals('Basic '.$apiKey, $catchRequestInfo['headers']['Authorization']);
        $this->assertEquals('/api/import/article-categories', $catchUri);
        $this->assertEquals(RequestActionCodeEnum::ACTION_CODE_DELETE, $primitiveAction['code']);
        $this->assertEquals($articleCategory->getId(), $primitiveAction['id']);
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @test
     * @dataProvider provideInvalidDeleteConstructorParameters
     * @expectedException Salamek\MojeOlomouc\Exception\InvalidArgumentException
     * @param IArticleCategory $articleCategory
     */
    public function deleteRequestShouldFailTest(IArticleCategory $articleCategory)
    {
        $apiKey = $this->getTestApiKey();

        $client = $this->getClientMock();

        $request = new Request($client, $apiKey);
        $articleCategories = new ArticleCategories($request);
        $articleCategories->delete([$articleCategory]);
    }

    /**
     * @return array
     */
    public function provideInvalidDeleteConstructorParameters(): array
    {
        return [
            [new ArticleCategory(
                'title-'.mt_rand(),
                null,
                false,
                true,
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
            [new ArticleCategory(
                'title-'.mt_rand(),
                null,
                false,
                true,
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
            [new ArticleCategory(
                'title-'.mt_rand(),
                null,
                false,
                true,
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
            [new ArticleCategory(
                'title-'.mt_rand()
            )],
            [new ArticleCategory(
                'title-'.mt_rand(),
                ArticleCategoryConsumerFlagEnum::STUDENT,
                true,
                false,
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
                false,
            ],
            [
                $this->getDateTime(),
                false,
                true,
                false,
            ],
            [
                null,
                true,
                true,
                false,
            ],
            [
                null,
                false,
                false,
                false,
            ],
            [
                null,
                false,
                true,
                false,
            ],
            [
                null,
                false,
                true,
                true,
            ]
        ];
    }
}