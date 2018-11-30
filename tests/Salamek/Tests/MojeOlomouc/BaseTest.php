<?php
declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc;


use GuzzleHttp\ClientInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

use Salamek\MojeOlomouc\Hydrator\IArticle;
use Salamek\MojeOlomouc\Hydrator\IArticleCategory;
use Salamek\MojeOlomouc\Hydrator\IEntityImage;
use Salamek\MojeOlomouc\Hydrator\IEvent;
use Salamek\MojeOlomouc\Hydrator\IEventCategory;
use Salamek\MojeOlomouc\Hydrator\IIdentifier;
use Salamek\MojeOlomouc\Hydrator\IImportantMessage;
use Salamek\MojeOlomouc\Hydrator\IPlace;
use Salamek\MojeOlomouc\Hydrator\IPlaceCategory;
use Salamek\MojeOlomouc\Model\Article;
use Salamek\MojeOlomouc\Model\ArticleCategory;
use Salamek\MojeOlomouc\Model\EntityImage;
use Salamek\MojeOlomouc\Model\Event;
use Salamek\MojeOlomouc\Model\EventCategory;
use Salamek\MojeOlomouc\Model\Identifier;
use Salamek\MojeOlomouc\Model\ImportantMessage;
use Salamek\MojeOlomouc\Model\Place;
use Salamek\MojeOlomouc\Model\PlaceCategory;

abstract class BaseTest extends TestCase
{
    protected function getResponseMock(): ResponseInterface
    {
        return $this->createMock('Psr\Http\Message\ResponseInterface');
    }

    protected function getStreamMock(): StreamInterface
    {
        return $this->createMock('Psr\Http\Message\StreamInterface');
    }

    protected function getClientMock(): ClientInterface
    {
        return $this->createMock('GuzzleHttp\ClientInterface');
    }

    protected function getTestApiKey(string $input = 'testKey'): string
    {
        return hash('sha256', $input);
    }

    protected function getResponseMockWithBody(string $responseContent, int $responseCode = 200): ResponseInterface
    {
        $streamMock = $this->createMock('Psr\Http\Message\StreamInterface');
        $streamMock->expects($this->once())
            ->method('getContents')
            ->will($this->returnValue($responseContent));

        $responseMock = $this->getResponseMock();
        $responseMock->expects($this->once())
            ->method('getBody')
            ->will($this->returnValue($streamMock));

        $responseMock->expects($this->once())
            ->method('getStatusCode')
            ->will($this->returnValue($responseCode));

        return $responseMock;
    }

    /**
     * @param bool $isError
     * @param string $message
     * @param int $code
     * @param array $data
     * @return array
     */
    protected function getFakeResponseData(bool $isError = false, string $message = 'OK', int $code = 0, array $data = []): array
    {
        $dataResponse = [
            'isError' => $isError,
            'message' => $message,
            'code' => $code,
            'data' => $data
        ];

        return $dataResponse;
    }

    /**
     * @return \DateTime
     * @throws \Exception
     */
    protected function getDateTime(): \DateTime
    {
        return \DateTime::createFromFormat(\DateTime::ISO8601, (new \DateTime())->format(\DateTime::ISO8601));
    }

    /**
     * @param bool $boolean
     * @return string
     */
    protected function boolToString(bool $boolean): string
    {
        return $boolean ? 'true': 'false';
    }

    /**
     * @param string $className
     * @return mixed
     */
    protected function getHydrator(string $className)
    {
        $hydrationTable = [];
        $hydrationTable[IIdentifier::class] = new \Salamek\MojeOlomouc\Hydrator\Identifier(Identifier::class);
        $hydrationTable[IEntityImage::class] = new \Salamek\MojeOlomouc\Hydrator\EntityImage(EntityImage::class);

        $hydrationTable[IArticleCategory::class] = new \Salamek\MojeOlomouc\Hydrator\ArticleCategory(ArticleCategory::class);
        $hydrationTable[IEventCategory::class] = new \Salamek\MojeOlomouc\Hydrator\EventCategory(EventCategory::class);
        $hydrationTable[IPlaceCategory::class] = new \Salamek\MojeOlomouc\Hydrator\PlaceCategory(PlaceCategory::class);

        $hydrationTable[IImportantMessage::class] = new \Salamek\MojeOlomouc\Hydrator\ImportantMessage(ImportantMessage::class);

        $hydrationTable[IArticle::class] = new \Salamek\MojeOlomouc\Hydrator\Article(Article::class, $hydrationTable[IEntityImage::class]);
        $hydrationTable[IEvent::class] = new \Salamek\MojeOlomouc\Hydrator\Event(Event::class, $hydrationTable[IEntityImage::class]);
        $hydrationTable[IPlace::class] = new \Salamek\MojeOlomouc\Hydrator\Place(Place::class, $hydrationTable[IEntityImage::class]);


        return $hydrationTable[$className];
    }
}