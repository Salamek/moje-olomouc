<?php

declare(strict_types=1);

namespace Salamek\MojeOlomouc;

use GuzzleHttp\ClientInterface;
use Salamek\MojeOlomouc\Enum\RequestActionCodeEnum;

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
        $defaultClientOptions = $this->buildDefaultClientOptions();
        $defaultClientOptions = array_merge($defaultClientOptions, ['query' => $arguments]);
        $response = $this->client->request('GET', $endpoint, $defaultClientOptions);
        return new Response($response, $hydratorMapping);
    }

    /**
     * @param string $endpoint
     * @param array $data
     * @return Response
     */
    public function create(string $endpoint, array $data): Response
    {
        $defaultClientOptions = $this->buildDefaultClientOptions();
        $defaultClientOptions['json'] = [
            'action' => [
                'code' => RequestActionCodeEnum::ACTION_CODE_CREATE,
                'id' => null
            ]
        ];

        $defaultClientOptions = array_merge_recursive($defaultClientOptions, ['json' => $data]);

        $response = $this->client->request('POST', $endpoint, $defaultClientOptions);
        return new Response($response);
    }

    /**
     * @param string $endpoint
     * @param int $id
     * @param array $data
     * @return Response
     */
    public function update(string $endpoint, int $id, array $data): Response
    {
        $defaultClientOptions = $this->buildDefaultClientOptions();
        $defaultClientOptions['json'] = [
            'action' => [
                'code' => RequestActionCodeEnum::ACTION_CODE_UPDATE,
                'id' => $id
            ]
        ];

        $defaultClientOptions = array_merge_recursive($defaultClientOptions, ['json' => $data]);
        $response = $this->client->request('POST', $endpoint, $defaultClientOptions);

        return new Response($response);
    }

    /**
     * @param string $endpoint
     * @param int $id
     * @return Response
     */
    public function delete(string $endpoint, int $id): Response
    {
        $defaultClientOptions = $this->buildDefaultClientOptions();
        $defaultClientOptions['json'] = [
            'action' => [
                'code' => RequestActionCodeEnum::ACTION_CODE_DELETE,
                'id' => $id
            ]
        ];

        $response = $this->client->request('POST', $endpoint, $defaultClientOptions);
        return new Response($response);
    }
}