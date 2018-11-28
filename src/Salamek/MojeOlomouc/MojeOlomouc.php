<?php

declare(strict_types=1);

namespace Salamek\MojeOlomouc;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Salamek\MojeOlomouc\Exception\InvalidArgumentException;
use Salamek\MojeOlomouc\Hydrator\IArticle;
use Salamek\MojeOlomouc\Hydrator\IArticleCategory;
use Salamek\MojeOlomouc\Hydrator\IEntityImage;
use Salamek\MojeOlomouc\Hydrator\IEvent;
use Salamek\MojeOlomouc\Hydrator\IEventCategory;
use Salamek\MojeOlomouc\Hydrator\IImportantMessage;
use Salamek\MojeOlomouc\Hydrator\IPlace;
use Salamek\MojeOlomouc\Hydrator\IPlaceCategory;
use Salamek\MojeOlomouc\Model\Article;
use Salamek\MojeOlomouc\Model\ArticleCategory;
use Salamek\MojeOlomouc\Model\EntityImage;
use Salamek\MojeOlomouc\Model\Event;
use Salamek\MojeOlomouc\Model\EventCategory;
use Salamek\MojeOlomouc\Model\Identifier;
use Salamek\MojeOlomouc\Model\IIdentifier;
use Salamek\MojeOlomouc\Model\ImportantMessage;
use Salamek\MojeOlomouc\Model\Place;
use Salamek\MojeOlomouc\Model\PlaceCategory;
use Salamek\MojeOlomouc\Operation\ArticleCategories;
use Salamek\MojeOlomouc\Operation\Articles;
use Salamek\MojeOlomouc\Operation\EventCategories;
use Salamek\MojeOlomouc\Operation\Events;
use Salamek\MojeOlomouc\Operation\ImportantMessages;
use Salamek\MojeOlomouc\Operation\PlaceCategories;
use Salamek\MojeOlomouc\Operation\Places;
use Salamek\MojeOlomouc\Validator\MaxLengthValidator;
use Salamek\MojeOlomouc\Validator\MinLengthValidator;

/**
 * Class MojeOlomouc
 * @package Salamek\MojeOlomouc
 */
class MojeOlomouc
{
    /** @var ClientInterface */
    private $client;

    /** @var string */
    private $apiKey;

    /** @var array */
    private $hydrationTable;

    /** @var ArticleCategories */
    public $articleCategories;

    /** @var Articles */
    public $articles;

    /** @var EventCategories */
    public $eventCategories;

    /** @var Events */
    public $events;

    /** @var ImportantMessages */
    public $importantMessages;

    /** @var PlaceCategories */
    public $placeCategories;

    /** @var Places */
    public $places;

    /**
     * MojeOlomouc constructor.
     * @param ClientInterface $client
     * @param string $apiKey
     * @param array $hydrationTable
     * @param bool $appendDefaultHydrationTable
     */
    public function __construct(
        ClientInterface $client,
        string $apiKey,
        array $hydrationTable = [],
        bool $appendDefaultHydrationTable = true
    )
    {
        MaxLengthValidator::validate($apiKey, 64);
        MinLengthValidator::validate($apiKey, 64);
        $this->client = $client;
        $this->apiKey = $apiKey;
        $this->hydrationTable = $hydrationTable;

        if (empty($hydrationTable) || $appendDefaultHydrationTable)
        {
            $this->buildDefaultHydrationTable();
        }

        $request = new Request($client, $apiKey);

        $this->articleCategories = new ArticleCategories($request, $this->hydrationTable[IArticleCategory::class]);
        $this->articles = new Articles($request, $this->hydrationTable[IArticle::class]);
        $this->eventCategories = new EventCategories($request, $this->hydrationTable[IEventCategory::class]);
        $this->events = new Events($request, $this->hydrationTable[IEvent::class]);
        $this->importantMessages = new ImportantMessages($request, $this->hydrationTable[IImportantMessage::class]);
        $this->placeCategories = new PlaceCategories($request, $this->hydrationTable[IPlaceCategory::class]);
        $this->places = new Places($request, $this->hydrationTable[IPlace::class]);
    }

    private function buildDefaultHydrationTable(): void
    {
        $this->hydrationTable[IEntityImage::class] = new \Salamek\MojeOlomouc\Hydrator\EntityImage(EntityImage::class);
        $this->hydrationTable[IIdentifier::class] = new \Salamek\MojeOlomouc\Hydrator\Identifier(Identifier::class);

        $this->hydrationTable[IArticleCategory::class] = new \Salamek\MojeOlomouc\Hydrator\ArticleCategory(ArticleCategory::class);
        $this->hydrationTable[IEventCategory::class] = new \Salamek\MojeOlomouc\Hydrator\EventCategory(EventCategory::class);
        $this->hydrationTable[IPlaceCategory::class] = new \Salamek\MojeOlomouc\Hydrator\PlaceCategory(PlaceCategory::class);

        $this->hydrationTable[IImportantMessage::class] = new \Salamek\MojeOlomouc\Hydrator\ImportantMessage(ImportantMessage::class);

        $this->hydrationTable[IArticle::class] = new \Salamek\MojeOlomouc\Hydrator\Article(Article::class, $this->hydrationTable[IEntityImage::class]);
        $this->hydrationTable[IEvent::class] = new \Salamek\MojeOlomouc\Hydrator\Event(Event::class, $this->hydrationTable[IEntityImage::class]);
        $this->hydrationTable[IPlace::class] = new \Salamek\MojeOlomouc\Hydrator\Place(Place::class, $this->hydrationTable[IEntityImage::class]);
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getOperation(string $name)
    {
        if (!property_exists($this, $name))
        {
            throw new InvalidArgumentException(sprintf('Operation %s not found', $name));
        }

        return $this->{$name};
    }

    /**
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * @return ClientInterface
     */
    public function getClient(): ClientInterface
    {
        return $this->client;
    }

    /**
     * @param string $apiKey
     * @param bool $isProduction
     * @return MojeOlomouc
     */
    public static function create(string $apiKey, bool $isProduction = false): MojeOlomouc
    {
        $guzzleHttpConfig = [
            'base_uri' => ($isProduction ? 'https://app.olomouc.eu': 'https://www.olomouc.app')
        ];

        return new MojeOlomouc(new Client($guzzleHttpConfig), $apiKey);
    }
}