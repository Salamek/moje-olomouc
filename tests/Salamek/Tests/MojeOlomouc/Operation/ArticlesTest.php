<?php

declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc;

use Salamek\MojeOlomouc\Enum\ArticleApproveStateEnum;
use Salamek\MojeOlomouc\Enum\RequestActionCodeEnum;
use Salamek\MojeOlomouc\Model\Article;
use Salamek\MojeOlomouc\Model\EntityImage;
use Salamek\MojeOlomouc\Model\IArticle;
use Salamek\MojeOlomouc\Operation\Articles;
use Salamek\MojeOlomouc\Request;
use Salamek\MojeOlomouc\Response;

class ArticlesTest extends BaseTest
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

        $article = new Articles($request);
        $response = $article->getAll(
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
        $this->assertEquals('/api/export/articles', $catchUri);
        $this->assertInstanceOf(Response::class, $response);
    }


    /**
     * @test
     * @dataProvider provideCreateConstructorParameters
     * @param IArticle $article
     */
    public function createShouldBeGoodTest(IArticle $article)
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
        $articles = new Articles($request);
        $response = $articles->create($article);

        $primitiveImages = [];
        foreach ($article->getImages() AS $image)
        {
            $primitiveImages[] = $image->toPrimitiveArray();
        }

        $this->assertInternalType('array', $catchRequestInfo['json']);
        $this->assertArrayHasKey('article', $catchRequestInfo['json']);
        $this->assertArrayHasKey('title', $catchRequestInfo['json']['article']);
        $this->assertArrayHasKey('content', $catchRequestInfo['json']['article']);
        $this->assertArrayHasKey('author', $catchRequestInfo['json']['article']);
        $this->assertArrayHasKey('categoryId', $catchRequestInfo['json']['article']);
        $this->assertArrayHasKey('dateTimeAt', $catchRequestInfo['json']['article']);
        $this->assertArrayHasKey('images', $catchRequestInfo['json']['article']);
        $this->assertArrayHasKey('attachmentUrl', $catchRequestInfo['json']['article']);
        $this->assertArrayHasKey('isVisible', $catchRequestInfo['json']['article']);
        $this->assertArrayHasKey('isImportant', $catchRequestInfo['json']['article']);
        $this->assertArrayHasKey('approveState', $catchRequestInfo['json']['article']);
        $this->assertEquals($article->getTitle(), $catchRequestInfo['json']['article']['title']);
        $this->assertEquals($article->getContent(), $catchRequestInfo['json']['article']['content']);
        $this->assertEquals($article->getAuthor(), $catchRequestInfo['json']['article']['author']);
        $this->assertEquals($article->getCategoryId(), $catchRequestInfo['json']['article']['categoryId']);
        $this->assertEquals($article->getDateTimeAt()->format(\DateTime::ISO8601), $catchRequestInfo['json']['article']['dateTimeAt']);
        $this->assertEquals($primitiveImages, $catchRequestInfo['json']['article']['images']);
        $this->assertEquals($article->getAttachmentUrl(), $catchRequestInfo['json']['article']['attachmentUrl']);
        $this->assertEquals($article->getIsVisible(), $catchRequestInfo['json']['article']['isVisible']);
        $this->assertEquals($article->getIsImportant(), $catchRequestInfo['json']['article']['isImportant']);
        $this->assertEquals($article->getApproveState(), $catchRequestInfo['json']['article']['approveState']);
        $this->assertEquals('POST', $catchType);
        $this->assertEquals('Basic '.$apiKey, $catchRequestInfo['headers']['Authorization']);
        $this->assertEquals('/api/import/articles', $catchUri);
        $this->assertEquals(RequestActionCodeEnum::ACTION_CODE_CREATE, $catchRequestInfo['json']['action']['code']);
        $this->assertEquals(null, $catchRequestInfo['json']['action']['id']);
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @test
     * @dataProvider provideUpdateConstructorParameters
     * @param IArticle $article
     * @param int|null $id
     */
    public function updateShouldBeGoodTest(IArticle $article, int $id = null)
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
        $articles = new Articles($request);
        $response = $articles->update($article, $id);

        $primitiveImages = [];
        foreach ($article->getImages() AS $image)
        {
            $primitiveImages[] = $image->toPrimitiveArray();
        }

        $this->assertInternalType('array', $catchRequestInfo['json']);
        $this->assertArrayHasKey('article', $catchRequestInfo['json']);
        $this->assertArrayHasKey('title', $catchRequestInfo['json']['article']);
        $this->assertArrayHasKey('content', $catchRequestInfo['json']['article']);
        $this->assertArrayHasKey('author', $catchRequestInfo['json']['article']);
        $this->assertArrayHasKey('categoryId', $catchRequestInfo['json']['article']);
        $this->assertArrayHasKey('dateTimeAt', $catchRequestInfo['json']['article']);
        $this->assertArrayHasKey('images', $catchRequestInfo['json']['article']);
        $this->assertArrayHasKey('attachmentUrl', $catchRequestInfo['json']['article']);
        $this->assertArrayHasKey('isVisible', $catchRequestInfo['json']['article']);
        $this->assertArrayHasKey('isImportant', $catchRequestInfo['json']['article']);
        $this->assertArrayHasKey('approveState', $catchRequestInfo['json']['article']);
        $this->assertEquals($article->getTitle(), $catchRequestInfo['json']['article']['title']);
        $this->assertEquals($article->getContent(), $catchRequestInfo['json']['article']['content']);
        $this->assertEquals($article->getAuthor(), $catchRequestInfo['json']['article']['author']);
        $this->assertEquals($article->getCategoryId(), $catchRequestInfo['json']['article']['categoryId']);
        $this->assertEquals($article->getDateTimeAt()->format(\DateTime::ISO8601), $catchRequestInfo['json']['article']['dateTimeAt']);
        $this->assertEquals($primitiveImages, $catchRequestInfo['json']['article']['images']);
        $this->assertEquals($article->getAttachmentUrl(), $catchRequestInfo['json']['article']['attachmentUrl']);
        $this->assertEquals($article->getIsVisible(), $catchRequestInfo['json']['article']['isVisible']);
        $this->assertEquals($article->getIsImportant(), $catchRequestInfo['json']['article']['isImportant']);
        $this->assertEquals($article->getApproveState(), $catchRequestInfo['json']['article']['approveState']);
        $this->assertEquals('POST', $catchType);
        $this->assertEquals('Basic '.$apiKey, $catchRequestInfo['headers']['Authorization']);
        $this->assertEquals('/api/import/articles', $catchUri);
        $this->assertEquals(RequestActionCodeEnum::ACTION_CODE_UPDATE, $catchRequestInfo['json']['action']['code']);
        $this->assertEquals((is_null($id) ? $article->getId() : $id), $catchRequestInfo['json']['action']['id']);
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @test
     * @dataProvider provideValidDeleteConstructorParameters
     * @param IArticle|null $article
     * @param int|null $id
     */
    public function deleteRequestShouldBeGoodTest(IArticle $article = null, int $id = null)
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
        $articles = new Articles($request);
        $response = $articles->delete($article, $id);

        $this->assertInternalType('array', $catchRequestInfo['json']);
        $this->assertEquals('POST', $catchType);
        $this->assertEquals('Basic '.$apiKey, $catchRequestInfo['headers']['Authorization']);
        $this->assertEquals('/api/import/articles', $catchUri);
        $this->assertEquals(RequestActionCodeEnum::ACTION_CODE_DELETE, $catchRequestInfo['json']['action']['code']);
        $this->assertEquals((is_null($id) ? $article->getId() : $id), $catchRequestInfo['json']['action']['id']);
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @test
     * @dataProvider provideInvalidDeleteConstructorParameters
     * @expectedException Salamek\MojeOlomouc\Exception\InvalidArgumentException
     * @param IArticle|null $article
     * @param int|null $id
     */
    public function deleteRequestShouldFailTest(IArticle $article = null, int $id = null)
    {
        $apiKey = $this->getTestApiKey();

        $client = $this->getClientMock();

        $request = new Request($client, $apiKey);
        $articles = new Articles($request);
        $articles->delete($article, $id);
    }

    /**
     * @return array
     */
    public function provideInvalidDeleteConstructorParameters(): array
    {
        return [
            [null, null],
            [new Article(
                'title-'.mt_rand(),
                'content-'.mt_rand(),
                'author-'.mt_rand(),
                mt_rand(),
                new \DateTime(),
                [new EntityImage('url-'.mt_rand())],
                'attachmentUrl-'.mt_rand(),
                false,
                true,
                ArticleApproveStateEnum::WAITING_FOR_ADD,
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
            [new Article(
                'title-'.mt_rand(),
                'content-'.mt_rand(),
                'author-'.mt_rand(),
                mt_rand(),
                new \DateTime(),
                [new EntityImage('url-'.mt_rand())],
                'attachmentUrl-'.mt_rand(),
                false,
                true,
                ArticleApproveStateEnum::WAITING_FOR_ADD,
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
            [new Article(
                'title-'.mt_rand(),
                'content-'.mt_rand(),
                'author-'.mt_rand(),
                mt_rand(),
                new \DateTime()
            ), mt_rand()],
            [new Article(
                'title-'.mt_rand(),
                'content-'.mt_rand(),
                'author-'.mt_rand(),
                mt_rand(),
                new \DateTime(),
                [new EntityImage('url-'.mt_rand())],
                'attachmentUrl-'.mt_rand(),
                false,
                true,
                ArticleApproveStateEnum::WAITING_FOR_ADD,
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
            [new Article(
                'title-'.mt_rand(),
                'content-'.mt_rand(),
                'author-'.mt_rand(),
                mt_rand(),
                new \DateTime()
            )],
            [new Article(
                'title-'.mt_rand(),
                'content-'.mt_rand(),
                'author-'.mt_rand(),
                mt_rand(),
                new \DateTime(),
                [new EntityImage('url-'.mt_rand())],
                'attachmentUrl-'.mt_rand(),
                false,
                true,
                ArticleApproveStateEnum::WAITING_FOR_ADD,
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