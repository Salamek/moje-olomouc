<?php

declare(strict_types=1);

namespace Salamek\MojeOlomouc;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Middleware;
use Salamek\MojeOlomouc\Enum\RequestActionCodeEnum;
use Salamek\MojeOlomouc\Model\IModel;
use Salamek\MojeOlomouc\Model\PayloadItem;

/**
 * Class Request
 * @package Salamek\MojeOlomouc
 */
class Request
{
    /** @var ClientInterface */
    private $client;

    /** @var string */
    private $apiKey;

    /**
     * Request constructor.
     * @param ClientInterface $client
     * @param string $apiKey
     */
    public function __construct(ClientInterface $client, string $apiKey)
    {
        $this->client = $client;
        $this->apiKey = $apiKey;
    }

    /**
     * @return array
     */
    private function buildDefaultClientOptions(): array
    {
        return [
            'http_errors' => false, // We are handling errors in Response our self
            'headers' => [
                'Authorization' => sprintf('Basic %s', $this->apiKey)
            ]
        ];
    }

    /**
     * @param string $endpoint
     * @param array $arguments
     * @param array $hydratorMapping
     * @return Response
     */
    public function get(string $endpoint, array $arguments = [], array $hydratorMapping = []): Response
    {
        // Process GET arguments
        array_walk ($arguments, function(&$item, $key){
            // Convert bool to strings, (default is to int==1)
            // Not sure if this is a best solution... (i mean using 'true' as true)
            if (is_bool($item))
            {
                $item = $item ? 'true': 'false';
            }
        });

        $defaultClientOptions = $this->buildDefaultClientOptions();
        $defaultClientOptions = array_merge($defaultClientOptions, ['query' => $arguments]);
        $response = $this->client->request('GET', $endpoint, $defaultClientOptions);
        return new Response($response, $hydratorMapping);
    }

    /**
     * @param string $endpoint
     * @param IModel[] $entities
     * @param string $dataKey
     * @return Response
     */
    public function create(string $endpoint, array $entities, string $dataKey): Response
    {
        return $this->post($endpoint, $this->createPayloadItemGenerator($entities, $dataKey, RequestActionCodeEnum::ACTION_CODE_CREATE));
    }

    /**
     * @param string $endpoint
     * @param IModel[] $entities
     * @param string $dataKey
     * @return Response
     */
    public function update(string $endpoint, array $entities, string $dataKey): Response
    {
        return $this->post($endpoint, $this->createPayloadItemGenerator($entities, $dataKey, RequestActionCodeEnum::ACTION_CODE_UPDATE));
    }

    /**
     * @param string $endpoint
     * @param IModel[] $entities
     * @param string $dataKey
     * @return Response
     */
    public function delete(string $endpoint, array $entities, string $dataKey): Response
    {
        return $this->post($endpoint, $this->createPayloadItemGenerator($entities, $dataKey, RequestActionCodeEnum::ACTION_CODE_DELETE));
    }

    /**
     * @param array $entities
     * @param string $dataKey
     * @param int $action
     * @return \Generator
     */
    private function createPayloadItemGenerator(array $entities, string $dataKey, int $action)
    {
        foreach ($entities AS $entity)
        {
            yield new PayloadItem($entity, $dataKey, $action);
        }
    }

    /**
     * @param string $endpoint
     * @param \Generator $payloadItems
     * @return Response
     * @internal
     */
    public function post(string $endpoint, \Generator $payloadItems)
    {
        $payloadArray = [];
        foreach($payloadItems AS $payloadItem)
        {
            $payloadArray[] = $payloadItem->toPrimitiveArray();
        }
        $defaultClientOptions = $this->buildDefaultClientOptions();
        $defaultClientOptions['json'] = $payloadArray;

        $response = $this->client->request('POST', $endpoint, $defaultClientOptions);

        return new Response($response);
    }
}