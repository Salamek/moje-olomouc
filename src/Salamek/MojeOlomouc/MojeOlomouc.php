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
use Salamek\MojeOlomouc\Model\ImportantMessage;
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

    /** @var Request */
    private $request;

    /** @var array */
    private $initiatedOperations = [];

    /** @var array */
    private $nameToOperation = [
        'articleCategories' => ArticleCategories::class,
        'articles' => Articles::class,
        'eventCategories' => EventCategories::class,
        'events' => Events::class,
        'importantMessages' => ImportantMessages::class,
        'placeCategories' => PlaceCategories::class,
        'places' => Places::class
    ];

    /** @var array */
    private $hydrationTable;

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
            'articleCategories' => ArticleCategory::class,
            'articles' => Article::class,
            'eventCategories' => EventCategory::class,
            'events' => Event::class,
            'importantMessages' => ImportantMessage::class,
            'placeCategories' => PlaceCategory::class,
            'places' => Place::class
        ]
    )
    {
        MaxLengthValidator::validate($apiKey, 64);
        MinLengthValidator::validate($apiKey, 64);
        $this->client = $client;
        $this->apiKey = $apiKey;
        $this->hydrationTable = $hydrationTable;

        $this->request = new Request($client, $apiKey);
    }

    /**
     * @param $name
     * @return IOperation
     */
    public function getOperation($name)
    {
        if (!array_key_exists($name, $this->nameToOperation))
        {
            throw new InvalidArgumentException(sprintf('Operation %s not found', $name));
        }

        if (!array_key_exists($name, $this->hydrationTable))
        {
            throw new InvalidArgumentException(sprintf('Hydrator for %s not found', $name));
        }

        if (array_key_exists($name, $this->initiatedOperations))
        {
            return $this->initiatedOperations[$name];
        }
        $operation = new $this->nameToOperation[$name]($this->request, $this->hydrationTable[$name]);
        $this->initiatedOperations[$name] = $operation;
        return $operation;
    }

    /**
     * @param $name
     * @return IOperation
     */
    public function __get($name)
    {
        return $this->getOperation($name);
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