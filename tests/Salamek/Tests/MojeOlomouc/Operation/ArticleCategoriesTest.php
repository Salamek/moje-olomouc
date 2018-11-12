<?php

declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc;

use Salamek\MojeOlomouc\Enum\ArticleCategoryConsumerFlagEnum;
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
        $this->assertEquals(($fromUpdatedAt ? $fromUpdatedAt->format(\DateTime::ISO8601) : null), $catchRequestInfo['query']['fromUpdatedAt']);
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
        $response = $articleCategories->create($articleCategory);

        $this->assertInternalType('array', $catchRequestInfo['json']);
        $this->assertArrayHasKey('articleCategory', $catchRequestInfo['json']);
        $this->assertArrayHasKey('title', $catchRequestInfo['json']['articleCategory']);
        $this->assertArrayHasKey('consumerFlags', $catchRequestInfo['json']['articleCategory']);
        $this->assertArrayHasKey('isImportant', $catchRequestInfo['json']['articleCategory']);
        $this->assertArrayHasKey('isVisible', $catchRequestInfo['json']['articleCategory']);

        $this->assertEquals($articleCategory->getTitle(), $catchRequestInfo['json']['articleCategory']['title']);
        $this->assertEquals($articleCategory->getConsumerFlags(), $catchRequestInfo['json']['articleCategory']['consumerFlags']);
        $this->assertEquals($articleCategory->getIsImportant(), $catchRequestInfo['json']['articleCategory']['isImportant']);
        $this->assertEquals($articleCategory->getIsVisible(), $catchRequestInfo['json']['articleCategory']['isVisible']);

        $this->assertEquals('POST', $catchType);
        $this->assertEquals('Basic '.$apiKey, $catchRequestInfo['headers']['Authorization']);
        $this->assertEquals('/api/import/article-categories', $catchUri);
        $this->assertEquals(RequestActionCodeEnum::ACTION_CODE_CREATE, $catchRequestInfo['json']['action']['code']);
        $this->assertEquals(null, $catchRequestInfo['json']['action']['id']);
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @test
     * @dataProvider provideUpdateConstructorParameters
     * @param IArticleCategory $articleCategory
     * @param int|null $id
     */
    public function updateShouldBeGoodTest(IArticleCategory $articleCategory, int $id = null)
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
        $response = $articleCategories->update($articleCategory, $id);

        $this->assertInternalType('array', $catchRequestInfo['json']);
        $this->assertArrayHasKey('articleCategory', $catchRequestInfo['json']);
        $this->assertArrayHasKey('title', $catchRequestInfo['json']['articleCategory']);
        $this->assertArrayHasKey('consumerFlags', $catchRequestInfo['json']['articleCategory']);
        $this->assertArrayHasKey('isImportant', $catchRequestInfo['json']['articleCategory']);
        $this->assertArrayHasKey('isVisible', $catchRequestInfo['json']['articleCategory']);

        $this->assertEquals($articleCategory->getTitle(), $catchRequestInfo['json']['articleCategory']['title']);
        $this->assertEquals($articleCategory->getConsumerFlags(), $catchRequestInfo['json']['articleCategory']['consumerFlags']);
        $this->assertEquals($articleCategory->getIsImportant(), $catchRequestInfo['json']['articleCategory']['isImportant']);
        $this->assertEquals($articleCategory->getIsVisible(), $catchRequestInfo['json']['articleCategory']['isVisible']);

        $this->assertEquals('POST', $catchType);
        $this->assertEquals('Basic '.$apiKey, $catchRequestInfo['headers']['Authorization']);
        $this->assertEquals('/api/import/article-categories', $catchUri);
        $this->assertEquals(RequestActionCodeEnum::ACTION_CODE_UPDATE, $catchRequestInfo['json']['action']['code']);
        $this->assertEquals((is_null($id) ? $articleCategory->getId() : $id), $catchRequestInfo['json']['action']['id']);
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @test
     * @dataProvider provideValidDeleteConstructorParameters
     * @param IArticleCategory|null $articleCategory
     * @param int|null $id
     */
    public function deleteRequestShouldBeGoodTest(IArticleCategory $articleCategory = null, int $id = null)
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
        $response = $articleCategories->delete($articleCategory, $id);

        $this->assertInternalType('array', $catchRequestInfo['json']);
        $this->assertEquals('POST', $catchType);
        $this->assertEquals('Basic '.$apiKey, $catchRequestInfo['headers']['Authorization']);
        $this->assertEquals('/api/import/article-categories', $catchUri);
        $this->assertEquals(RequestActionCodeEnum::ACTION_CODE_DELETE, $catchRequestInfo['json']['action']['code']);
        $this->assertEquals((is_null($id) ? $articleCategory->getId() : $id), $catchRequestInfo['json']['action']['id']);
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @test
     * @dataProvider provideInvalidDeleteConstructorParameters
     * @expectedException Salamek\MojeOlomouc\Exception\InvalidArgumentException
     * @param IArticleCategory|null $articleCategory
     * @param int|null $id
     */
    public function deleteRequestShouldFailTest(IArticleCategory $articleCategory = null, int $id = null)
    {
        $apiKey = $this->getTestApiKey();

        $client = $this->getClientMock();

        $request = new Request($client, $apiKey);
        $articleCategories = new ArticleCategories($request);
        $articleCategories->delete($articleCategory, $id);
    }

    /**
     * @return array
     */
    public function provideInvalidDeleteConstructorParameters(): array
    {
        return [
            [null, null],
            [new ArticleCategory(
                'title-'.mt_rand(),
                null,
                false,
                true,
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
            [new ArticleCategory(
                'title-'.mt_rand(),
                null,
                false,
                true,
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
            [new ArticleCategory(
                'title-'.mt_rand(),
                null,
                false,
                true,
                null
            ), mt_rand()],
            [new ArticleCategory(
                'title-'.mt_rand(),
                null,
                false,
                true,
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
                new \DateTime(),
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