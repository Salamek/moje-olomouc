<?php

declare(strict_types=1);

namespace Salamek\MojeOlomouc;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Salamek\MojeOlomouc\Exception\InvalidArgumentException;
use Salamek\MojeOlomouc\Model\Article;
use Salamek\MojeOlomouc\Model\ArticleCategory;
use Salamek\MojeOlomouc\Model\Event;
use Salamek\MojeOlomouc\Model\EventCategory;
use Salamek\MojeOlomouc\Model\IArticle;
use Salamek\MojeOlomouc\Model\IArticleCategory;
use Salamek\MojeOlomouc\Model\IEvent;
use Salamek\MojeOlomouc\Model\IEventCategory;
use Salamek\MojeOlomouc\Model\IImportantMessage;
use Salamek\MojeOlomouc\Model\ImportantMessage;
use Salamek\MojeOlomouc\Model\IPlace;
use Salamek\MojeOlomouc\Model\IPlaceCategory;
use Salamek\MojeOlomouc\Model\Place;
use Salamek\MojeOlomouc\Model\PlaceCategory;
use Salamek\MojeOlomouc\Operation\ArticleCategories;
use Salamek\MojeOlomouc\Operation\Articles;
use Salamek\MojeOlomouc\Operation\EventCategories;
use Salamek\MojeOlomouc\Operation\Events;
use Salamek\MojeOlomouc\Operation\ImportantMessages;
use Salamek\MojeOlomouc\Operation\IOperation;
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
     */
    public function __construct(
        ClientInterface $client,
        string $apiKey,
        array $hydrationTable = [
            IArticleCategory::class => ArticleCategory::class,
            IArticle::class => Article::class,
            IEventCategory::class => EventCategory::class,
            IEvent::class => Event::class,
            IImportantMessage::class => ImportantMessage::class,
            IPlaceCategory::class => PlaceCategory::class,
            IPlace::class => Place::class
        ]
    )
    {
        MaxLengthValidator::validate($apiKey, 64);
        MinLengthValidator::validate($apiKey, 64);
        $this->client = $client;
        $this->apiKey = $apiKey;
        $this->hydrationTable = $hydrationTable;

        $request = new Request($client, $apiKey);

        $this->articleCategories = new ArticleCategories($request, $this->getHydrator(IArticleCategory::class));
        $this->articles = new Articles($request, $this->getHydrator(IArticle::class));
        $this->eventCategories = new EventCategories($request, $this->getHydrator(IEventCategory::class));
        $this->events = new Events($request, $this->getHydrator(IEvent::class));
        $this->importantMessages = new ImportantMessages($request, $this->getHydrator(IImportantMessage::class));
        $this->placeCategories = new PlaceCategories($request, $this->getHydrator(IPlaceCategory::class));
        $this->places = new Places($request, $this->getHydrator(IPlace::class));
    }

    /**
     * @param string $name
     * @return string
     */
    private function getHydrator(string $name): string
    {
        if (!array_key_exists($name, $this->hydrationTable))
        {
            throw new InvalidArgumentException(sprintf('Hydrator for %s not found', $name));
        }

        return $this->hydrationTable[$name];
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