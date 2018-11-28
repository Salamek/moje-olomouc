<?php

declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc;

use Salamek\MojeOlomouc\Enum\ArticleApproveStateEnum;
use Salamek\MojeOlomouc\Enum\ArticleSourceEnum;
use Salamek\MojeOlomouc\Enum\DateTime;
use Salamek\MojeOlomouc\Enum\RequestActionCodeEnum;
use Salamek\MojeOlomouc\Model\Article;
use Salamek\MojeOlomouc\Model\EntityImage;
use Salamek\MojeOlomouc\Model\IArticle;
use Salamek\MojeOlomouc\Operation\Articles;
use Salamek\MojeOlomouc\Request;
use Salamek\MojeOlomouc\Response;

class ArticlesTest extends BaseTest
{
    private $hydrator;

    /** @var \Salamek\MojeOlomouc\Hydrator\IEntityImage */
    private $entityImageHydrator;

    public function setUp()
    {
        parent::setUp();

        $this->hydrator = $this->getHydrator(\Salamek\MojeOlomouc\Hydrator\IArticle::class);
        $this->entityImageHydrator = $this->getHydrator(\Salamek\MojeOlomouc\Hydrator\IEntityImage::class);
    }

    /**
     * @test
     * @dataProvider provideGetAllConstructorParameters
     * @param \DateTimeInterface|null $from
     * @param bool $deleted
     * @param bool $invisible
     * @param bool $withExtraFields
     * @param string $source
     * @param bool $own
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getAllShouldBeGoodTest(
        \DateTimeInterface $from = null,
        bool $deleted = false,
        bool $invisible = false,
        bool $withExtraFields = false,
        string $source = ArticleSourceEnum::PUBLISHED,
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

        $article = new Articles($request, $this->hydrator);
        $response = $article->getAll(
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
        $this->assertEquals('/api/export/articles', $catchUri);
        $this->assertInstanceOf(Response::class, $response);
    }


    /**
     * @test
     * @dataProvider provideCreateConstructorParameters
     * @param IArticle $article
     * @throws \GuzzleHttp\Exception\GuzzleException
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
        $articles = new Articles($request, $this->hydrator);
        $response = $articles->create([$article]);

        $primitiveImages = [];
        foreach ($article->getImages() AS $image)
        {
            $primitiveImages[] = $this->entityImageHydrator->toPrimitiveArray($image);
        }

        $primitivePayloadItem = $catchRequestInfo['json'][0];

        $this->assertInternalType('array', $primitivePayloadItem);
        $this->assertArrayHasKey('article', $primitivePayloadItem);
        $this->assertArrayHasKey('action', $primitivePayloadItem);

        $primitiveArticle = $primitivePayloadItem['article'];
        $primitiveAction = $primitivePayloadItem['action'];

        $this->assertInternalType('array', $primitiveArticle);
        $this->assertInternalType('array', $primitiveAction);
        $this->assertArrayHasKey('title', $primitiveArticle);
        $this->assertArrayHasKey('content', $primitiveArticle);
        $this->assertArrayHasKey('author', $primitiveArticle);
        $this->assertArrayHasKey('categoryId', $primitiveArticle);
        $this->assertArrayHasKey('dateTimeAt', $primitiveArticle);
        $this->assertArrayHasKey('images', $primitiveArticle);
        if (!is_null($article->getAttachmentUrl())) $this->assertArrayHasKey('attachmentUrl', $primitiveArticle);
        if (!is_null($article->getIsVisible())) $this->assertArrayHasKey('isVisible', $primitiveArticle);
        if (!is_null($article->getIsImportant())) $this->assertArrayHasKey('isImportant', $primitiveArticle);
        if (!is_null($article->getApproveState())) $this->assertArrayHasKey('approveState', $primitiveArticle);
        $this->assertEquals($article->getTitle(), $primitiveArticle['title']);
        $this->assertEquals($article->getContent(), $primitiveArticle['content']);
        $this->assertEquals($article->getAuthor(), $primitiveArticle['author']);
        $this->assertEquals($article->getCategoryId(), $primitiveArticle['categoryId']);
        $this->assertEquals($article->getDateTimeAt()->format(DateTime::NOT_A_ISO8601), $primitiveArticle['dateTimeAt']);
        $this->assertEquals($primitiveImages, $primitiveArticle['images']);
        if (!is_null($article->getAttachmentUrl())) $this->assertEquals($article->getAttachmentUrl(), $primitiveArticle['attachmentUrl']);
        if (!is_null($article->getIsVisible())) $this->assertEquals($article->getIsVisible(), $primitiveArticle['isVisible']);
        if (!is_null($article->getIsImportant())) $this->assertEquals($article->getIsImportant(), $primitiveArticle['isImportant']);
        if (!is_null($article->getApproveState())) $this->assertEquals($article->getApproveState(), $primitiveArticle['approveState']);
        $this->assertEquals('POST', $catchType);
        $this->assertEquals('Basic '.$apiKey, $catchRequestInfo['headers']['Authorization']);
        $this->assertEquals('/api/import/articles', $catchUri);
        $this->assertEquals(RequestActionCodeEnum::ACTION_CODE_CREATE, $primitiveAction['code']);
        $this->assertEquals($article->getEntityIdentifier(), $primitiveAction['id']);
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @test
     * @dataProvider provideUpdateConstructorParameters
     * @param IArticle $article
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function updateShouldBeGoodTest(IArticle $article)
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
        $articles = new Articles($request, $this->hydrator);
        $response = $articles->update([$article]);

        $primitiveImages = [];
        foreach ($article->getImages() AS $image)
        {
            $primitiveImages[] = $this->entityImageHydrator->toPrimitiveArray($image);
        }


        $primitivePayloadItem = $catchRequestInfo['json'][0];

        $this->assertInternalType('array', $primitivePayloadItem);
        $this->assertArrayHasKey('article', $primitivePayloadItem);
        $this->assertArrayHasKey('action', $primitivePayloadItem);

        $primitiveArticle = $primitivePayloadItem['article'];
        $primitiveAction = $primitivePayloadItem['action'];

        $this->assertInternalType('array', $primitiveArticle);
        $this->assertInternalType('array', $primitiveAction);
        $this->assertArrayHasKey('title', $primitiveArticle);
        $this->assertArrayHasKey('content', $primitiveArticle);
        $this->assertArrayHasKey('author', $primitiveArticle);
        $this->assertArrayHasKey('categoryId', $primitiveArticle);
        $this->assertArrayHasKey('dateTimeAt', $primitiveArticle);
        $this->assertArrayHasKey('images', $primitiveArticle);
        if (!is_null($article->getAttachmentUrl())) $this->assertArrayHasKey('attachmentUrl', $primitiveArticle);
        if (!is_null($article->getIsVisible())) $this->assertArrayHasKey('isVisible', $primitiveArticle);
        if (!is_null($article->getIsImportant())) $this->assertArrayHasKey('isImportant', $primitiveArticle);
        if (!is_null($article->getApproveState())) $this->assertArrayHasKey('approveState', $primitiveArticle);
        $this->assertEquals($article->getTitle(), $primitiveArticle['title']);
        $this->assertEquals($article->getContent(), $primitiveArticle['content']);
        $this->assertEquals($article->getAuthor(), $primitiveArticle['author']);
        $this->assertEquals($article->getCategoryId(), $primitiveArticle['categoryId']);
        $this->assertEquals($article->getDateTimeAt()->format(DateTime::NOT_A_ISO8601), $primitiveArticle['dateTimeAt']);
        $this->assertEquals($primitiveImages, $primitiveArticle['images']);
        if (!is_null($article->getAttachmentUrl())) $this->assertEquals($article->getAttachmentUrl(), $primitiveArticle['attachmentUrl']);
        if (!is_null($article->getIsVisible())) $this->assertEquals($article->getIsVisible(), $primitiveArticle['isVisible']);
        if (!is_null($article->getIsImportant())) $this->assertEquals($article->getIsImportant(), $primitiveArticle['isImportant']);
        if (!is_null($article->getApproveState())) $this->assertEquals($article->getApproveState(), $primitiveArticle['approveState']);
        $this->assertEquals('POST', $catchType);
        $this->assertEquals('Basic '.$apiKey, $catchRequestInfo['headers']['Authorization']);
        $this->assertEquals('/api/import/articles', $catchUri);
        $this->assertEquals(RequestActionCodeEnum::ACTION_CODE_UPDATE, $primitiveAction['code']);
        $this->assertEquals($article->getEntityIdentifier(), $primitiveAction['id']);
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @test
     * @dataProvider provideValidDeleteConstructorParameters
     * @param IArticle|null $article
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function deleteRequestShouldBeGoodTest(IArticle $article = null)
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
        $articles = new Articles($request, $this->hydrator);
        $response = $articles->delete([$article]);

        $primitivePayloadItem = $catchRequestInfo['json'][0];
        $this->assertInternalType('array', $primitivePayloadItem);
        $this->assertArrayHasKey('action', $primitivePayloadItem);

        $primitiveAction = $primitivePayloadItem['action'];

        $this->assertInternalType('array', $primitiveAction);

        $this->assertEquals('POST', $catchType);
        $this->assertEquals('Basic '.$apiKey, $catchRequestInfo['headers']['Authorization']);
        $this->assertEquals('/api/import/articles', $catchUri);
        $this->assertEquals(RequestActionCodeEnum::ACTION_CODE_DELETE, $primitiveAction['code']);
        $this->assertEquals($article->getEntityIdentifier(), $primitiveAction['id']);
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @test
     * @dataProvider provideInvalidDeleteConstructorParameters
     * @expectedException \Salamek\MojeOlomouc\Exception\InvalidArgumentException
     * @param IArticle $article
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function deleteRequestShouldFailTest(IArticle $article)
    {
        $apiKey = $this->getTestApiKey();

        $client = $this->getClientMock();

        $request = new Request($client, $apiKey);
        $articles = new Articles($request, $this->hydrator);
        $articles->delete([$article]);
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function provideInvalidDeleteConstructorParameters(): array
    {
        return [
            [new Article(
                'title-'.mt_rand(),
                'content-'.mt_rand(),
                'author-'.mt_rand(),
                mt_rand(),
                $this->getDateTime(),
                [new EntityImage('url-'.mt_rand())],
                'attachmentUrl-'.mt_rand(),
                false,
                true,
                ArticleApproveStateEnum::WAITING_FOR_ADD,
                null
            )]
        ];
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function provideValidDeleteConstructorParameters(): array
    {
        return [
            [new Article(
                'title-'.mt_rand(),
                'content-'.mt_rand(),
                'author-'.mt_rand(),
                mt_rand(),
                $this->getDateTime(),
                [new EntityImage('url-'.mt_rand())],
                'attachmentUrl-'.mt_rand(),
                false,
                true,
                ArticleApproveStateEnum::WAITING_FOR_ADD,
                mt_rand()
            )]
        ];
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function provideUpdateConstructorParameters(): array
    {
        return [
            [new Article(
                'title-'.mt_rand(),
                'content-'.mt_rand(),
                'author-'.mt_rand(),
                mt_rand(),
                $this->getDateTime(),
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
     * @throws \Exception
     */
    public function provideCreateConstructorParameters(): array
    {
        return [
            [new Article(
                'title-'.mt_rand(),
                'content-'.mt_rand(),
                'author-'.mt_rand(),
                mt_rand(),
                $this->getDateTime()
            )],
            [new Article(
                'title-'.mt_rand(),
                'content-'.mt_rand(),
                'author-'.mt_rand(),
                mt_rand(),
                $this->getDateTime(),
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
     * @throws \Exception
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