<?php

declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

use Salamek\MojeOlomouc\Enum\ArticleApproveStateEnum;
use Salamek\MojeOlomouc\Enum\ArticleSourceEnum;
use Salamek\MojeOlomouc\Enum\DateTime;
use Salamek\MojeOlomouc\Enum\RequestActionCodeEnum;
use Salamek\MojeOlomouc\Model\Article;
use Salamek\MojeOlomouc\Model\EntityImage;
use Salamek\MojeOlomouc\Model\IArticle;
use Salamek\MojeOlomouc\Model\Identifier;
use Salamek\MojeOlomouc\Operation\Articles;
use Salamek\MojeOlomouc\Request;
use Salamek\MojeOlomouc\Response;

class ArticlesTest extends BaseTest
{
    private $hydrator;

    /** @var \Salamek\MojeOlomouc\Hydrator\IEntityImage */
    private $entityImageHydrator;

    public function setUp(): void
    {
        parent::setUp();

        $this->hydrator = $this->getHydrator(\Salamek\MojeOlomouc\Hydrator\IArticle::class);
        $this->entityImageHydrator = $this->getHydrator(\Salamek\MojeOlomouc\Hydrator\IEntityImage::class);
    }

    /**
     * @param \DateTimeInterface|null $from
     * @param bool $deleted
     * @param bool $invisible
     * @param bool $withExtraFields
     * @param string $source
     * @param bool $own
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
#[Test]
#[DataProvider('provideGetAllConstructorParameters')]

    public function getAllShouldBeGoodTest(
        ?\DateTimeInterface $from = null,
        bool $deleted = false,
        bool $invisible = false,
        bool $withExtraFields = false,
        string $source = ArticleSourceEnum::PUBLISHED,
        bool $own = false
    ): void
    {
        $apiKey = self::getTestApiKey();

        $catchType = null;
        $catchUri = null;
        $catchRequestInfo = null;
        $responseData = $this->getFakeResponseData();
        $responseDataString = json_encode($responseData);

        $response = $this->getResponseMockWithBody($responseDataString);

        $client = $this->getClientMock();
        $client->expects($this->once())
            ->method('request')
            ->willReturnCallback(function ($type, $uri, $requestInfo) use (&$catchRequestInfo, &$catchType, &$catchUri, $response) {
                $catchType = $type;
                $catchUri = $uri;
                $catchRequestInfo = $requestInfo;
                return $response;
            });


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

        $this->assertIsArray($catchRequestInfo['query']);
        $this->assertArrayHasKey('from', $catchRequestInfo['query']);
        $this->assertArrayHasKey('deleted', $catchRequestInfo['query']);
        $this->assertArrayHasKey('invisible', $catchRequestInfo['query']);
        $this->assertArrayHasKey('withExtraFields', $catchRequestInfo['query']);
        $this->assertArrayHasKey('source', $catchRequestInfo['query']);
        $this->assertArrayHasKey('own', $catchRequestInfo['query']);
        $this->assertEquals(($from ? $from->format(DateTime::A_ISO8601) : null), $catchRequestInfo['query']['from']);
        $this->assertEquals(self::boolToString($deleted), $catchRequestInfo['query']['deleted']);
        $this->assertEquals(self::boolToString($invisible), $catchRequestInfo['query']['invisible']);
        $this->assertEquals(self::boolToString($withExtraFields), $catchRequestInfo['query']['withExtraFields']);
        $this->assertEquals($source, $catchRequestInfo['query']['source']);
        $this->assertEquals(self::boolToString($own), $catchRequestInfo['query']['own']);
        $this->assertEquals('GET', $catchType);
        $this->assertEquals('Basic '.$apiKey, $catchRequestInfo['headers']['Authorization']);
        $this->assertEquals('/api/export/articles', $catchUri);
        $this->assertInstanceOf(Response::class, $response);
    }


    /**
     * @param IArticle $article
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
#[Test]
#[DataProvider('provideCreateConstructorParameters')]

    public function createShouldBeGoodTest(IArticle $article)
    {
        $apiKey = self::getTestApiKey();

        $catchType = null;
        $catchUri = null;
        $catchRequestInfo = null;
        $responseData = $this->getFakeResponseData();
        $responseDataString = json_encode($responseData);

        $response = $this->getResponseMockWithBody($responseDataString);

        $client = $this->getClientMock();
        $client->expects($this->once())
            ->method('request')
            ->willReturnCallback(function ($type, $uri, $requestInfo) use (&$catchRequestInfo, &$catchType, &$catchUri, $response) {
                $catchType = $type;
                $catchUri = $uri;
                $catchRequestInfo = $requestInfo;
                return $response;
            });

        $request = new Request($client, $apiKey);
        $articles = new Articles($request, $this->hydrator);
        $response = $articles->create([$article]);

        $primitiveImages = [];
        foreach ($article->getImages() AS $image)
        {
            $primitiveImages[] = $this->entityImageHydrator->toPrimitiveArray($image);
        }

        $primitivePayloadItem = $catchRequestInfo['json'][0];

        $this->assertIsArray($primitivePayloadItem);
        $this->assertArrayHasKey('article', $primitivePayloadItem);
        $this->assertArrayHasKey('action', $primitivePayloadItem);

        $primitiveArticle = $primitivePayloadItem['article'];
        $primitiveAction = $primitivePayloadItem['action'];

        $this->assertIsArray($primitiveArticle);
        $this->assertIsArray($primitiveAction);
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
        $this->assertEquals($article->getCategory()->getEntityIdentifier(), $primitiveArticle['categoryId']);
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
     * @param IArticle $article
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
#[Test]
#[DataProvider('provideUpdateConstructorParameters')]

    public function updateShouldBeGoodTest(IArticle $article)
    {
        $apiKey = self::getTestApiKey();

        $catchType = null;
        $catchUri = null;
        $catchRequestInfo = null;
        $responseData = $this->getFakeResponseData();
        $responseDataString = json_encode($responseData);

        $response = $this->getResponseMockWithBody($responseDataString);

        $client = $this->getClientMock();
        $client->expects($this->once())
            ->method('request')
            ->willReturnCallback(function ($type, $uri, $requestInfo) use (&$catchRequestInfo, &$catchType, &$catchUri, $response) {
                $catchType = $type;
                $catchUri = $uri;
                $catchRequestInfo = $requestInfo;
                return $response;
            });

        $request = new Request($client, $apiKey);
        $articles = new Articles($request, $this->hydrator);
        $response = $articles->update([$article]);

        $primitiveImages = [];
        foreach ($article->getImages() AS $image)
        {
            $primitiveImages[] = $this->entityImageHydrator->toPrimitiveArray($image);
        }


        $primitivePayloadItem = $catchRequestInfo['json'][0];

        $this->assertIsArray($primitivePayloadItem);
        $this->assertArrayHasKey('article', $primitivePayloadItem);
        $this->assertArrayHasKey('action', $primitivePayloadItem);

        $primitiveArticle = $primitivePayloadItem['article'];
        $primitiveAction = $primitivePayloadItem['action'];

        $this->assertIsArray($primitiveArticle);
        $this->assertIsArray($primitiveAction);
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
        $this->assertEquals($article->getCategory()->getEntityIdentifier(), $primitiveArticle['categoryId']);
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
     * @param IArticle|null $article
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
#[Test]
#[DataProvider('provideValidDeleteConstructorParameters')]

    public function deleteRequestShouldBeGoodTest(?IArticle $article = null)
    {
        $apiKey = self::getTestApiKey();

        $catchType = null;
        $catchUri = null;
        $catchRequestInfo = null;
        $responseData = $this->getFakeResponseData();
        $responseDataString = json_encode($responseData);

        $response = $this->getResponseMockWithBody($responseDataString);

        $client = $this->getClientMock();
        $client->expects($this->once())
            ->method('request')
            ->willReturnCallback(function ($type, $uri, $requestInfo) use (&$catchRequestInfo, &$catchType, &$catchUri, $response) {
                $catchType = $type;
                $catchUri = $uri;
                $catchRequestInfo = $requestInfo;
                return $response;
            });

        $request = new Request($client, $apiKey);
        $articles = new Articles($request, $this->hydrator);
        $response = $articles->delete([$article]);

        $primitivePayloadItem = $catchRequestInfo['json'][0];
        $this->assertIsArray($primitivePayloadItem);
        $this->assertArrayHasKey('action', $primitivePayloadItem);

        $primitiveAction = $primitivePayloadItem['action'];

        $this->assertIsArray($primitiveAction);

        $this->assertEquals('POST', $catchType);
        $this->assertEquals('Basic '.$apiKey, $catchRequestInfo['headers']['Authorization']);
        $this->assertEquals('/api/import/articles', $catchUri);
        $this->assertEquals(RequestActionCodeEnum::ACTION_CODE_DELETE, $primitiveAction['code']);
        $this->assertEquals($article->getEntityIdentifier(), $primitiveAction['id']);
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @param IArticle $article
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
#[Test]
#[DataProvider('provideInvalidDeleteConstructorParameters')]

    public function deleteRequestShouldFailTest(IArticle $article)
    {
        $this->expectException(\Salamek\MojeOlomouc\Exception\InvalidArgumentException::class);
        $apiKey = self::getTestApiKey();

        $client = $this->getClientStub();

        $request = new Request($client, $apiKey);
        $articles = new Articles($request, $this->hydrator);
        $articles->delete([$article]);
    }

    /**
     * @return array
     * @throws \Exception
     */

    public static function provideInvalidDeleteConstructorParameters(): array
    {
        return [
            [new Article(
                'title-'.mt_rand(),
                'content-'.mt_rand(),
                'author-'.mt_rand(),
                new Identifier(mt_rand()),
                self::getDateTime(),
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

    public static function provideValidDeleteConstructorParameters(): array
    {
        return [
            [new Article(
                'title-'.mt_rand(),
                'content-'.mt_rand(),
                'author-'.mt_rand(),
                new Identifier(mt_rand()),
                self::getDateTime(),
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

    public static function provideUpdateConstructorParameters(): array
    {
        return [
            [new Article(
                'title-'.mt_rand(),
                'content-'.mt_rand(),
                'author-'.mt_rand(),
                new Identifier(mt_rand()),
                self::getDateTime(),
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

    public static function provideCreateConstructorParameters(): array
    {
        return [
            [new Article(
                'title-'.mt_rand(),
                'content-'.mt_rand(),
                'author-'.mt_rand(),
                new Identifier(mt_rand()),
                self::getDateTime()
            )],
            [new Article(
                'title-'.mt_rand(),
                'content-'.mt_rand(),
                'author-'.mt_rand(),
                new Identifier(mt_rand()),
                self::getDateTime(),
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

    public static function provideGetAllConstructorParameters(): array
    {
        return [
            [
                null,
                false,
                true,
                true,
                ArticleSourceEnum::PUBLISHED,
                false,
            ],
            [
                self::getDateTime(),
                false,
                true,
                true,
                ArticleSourceEnum::PUBLISHED,
                false,
            ],
            [
                null,
                true,
                true,
                true,
                ArticleSourceEnum::PUBLISHED,
                false,
            ],
            [
                null,
                false,
                false,
                true,
                ArticleSourceEnum::PUBLISHED,
                false,
            ],
            [
                null,
                false,
                true,
                false,
                ArticleSourceEnum::PUBLISHED,
                false,
            ],
            [
                null,
                false,
                true,
                true,
                ArticleSourceEnum::PUBLISHED,
                true,
            ]
        ];
    }
}
