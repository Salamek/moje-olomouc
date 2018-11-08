<?php

declare(strict_types=1);

namespace Salamek\MojeOlomouc;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Salamek\MojeOlomouc\Operation\Event;
use Salamek\MojeOlomouc\Operation\ImportantMessage;

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

    /** @var Event */
    public $event;

    // @TODO
    public $eventCategory;

    // @TODO
    public $article;

    // @TODO
    public $articleCategory;

    // @TODO
    public $place;

    // @TODO
    public $placeCategory;

    public $importantMessage;


    /**
     * MojeOlomouc constructor.
     * @param ClientInterface $client
     * @param string $apiKey
     */
    public function __construct(ClientInterface $client, string $apiKey)
    {
        $this->client = $client;
        $this->apiKey = $apiKey;

        $request = new Request($client, $apiKey);

        $this->event = new Event($request);
        $this->importantMessage = new ImportantMessage($request);
    }

    /**
     * @return bool
     */
    public function isSetup(): bool
    {
        return '' !== trim($this->apiKey);
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