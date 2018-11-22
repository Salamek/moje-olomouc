<?php

declare(strict_types=1);

namespace Salamek\MojeOlomouc;

use Psr\Http\Message\ResponseInterface;
use Salamek\MojeOlomouc\Exception\InvalidJsonResponseException;
use Salamek\MojeOlomouc\Model\IModel;

/**
 * Class Response
 * @package Salamek\MojeOlomouc
 */
class Response
{
    /** @var bool */
    private $isError = false;

    /** @var int */
    private $httpCode;

    /** @var  */
    private $rawData;

    /** @var object */
    private $data = null;

    /** @var int */
    private $code;

    /** @var string */
    private $message;

    /**
     * Response constructor.
     * @param ResponseInterface $response
     * @param array $hydratorMapping
     * @param IModel[] $idHydrators
     */
    public function __construct(ResponseInterface $response, array $hydratorMapping = [], array $idHydrators = [])
    {
        $this->httpCode = $response->getStatusCode();
        $rawContents = $response->getBody()->getContents();
        $this->rawData = json_decode($rawContents, true);

        if (!$this->rawData){
            throw new InvalidJsonResponseException(sprintf('getContents returned malformed or none JSON string (%s)', $rawContents));
        }

        if (!array_key_exists('message', $this->rawData))
        {
            throw new InvalidJsonResponseException(sprintf('message is missing in JSON data (%s)', $rawContents));
        }

        if (!array_key_exists('code', $this->rawData))
        {
            throw new InvalidJsonResponseException(sprintf('code is missing in JSON data (%s)', $rawContents));
        }

        if (array_key_exists('isError', $this->rawData))
        {
            $this->isError = $this->rawData['isError'];
        }

        if (!$this->isError && !array_key_exists('data', $this->rawData))
        {
            throw new InvalidJsonResponseException(sprintf('data is missing in success JSON data (%s)', $rawContents));
        }

        $this->message = $this->rawData['message'];
        $this->code = $this->rawData['code'];

        if (array_key_exists('data', $this->rawData))
        {
            $this->data = $this->rawData['data'];

            //Use hydrator to modify data
            foreach($hydratorMapping AS $itemKey => $hydrator)
            {

                if (isset($this->data[$itemKey]))
                {
                    $hydratedItems = [];
                    foreach ($this->data[$itemKey] AS $item)
                    {
                        $hydratedItems[] = call_user_func(sprintf('%s::fromPrimitiveArray', $hydrator), $item);
                    }

                    $this->data[$itemKey] = $hydratedItems;
                }
            }

            //Append IDs
            foreach ($idHydrators AS $k => $idHydrator)
            {
                // Lets hope that is returned in correct order
                if (array_key_exists($k, $this->data))
                {
                    $modelResponse = $this->data[$k];
                    // Error codes are <0
                    if ($modelResponse['code'] > -1 && array_key_exists('data', $modelResponse) && array_key_exists('id', $modelResponse['data']))
                    {
                        $idHydrator->setId($modelResponse['data']['id']);
                    }
                }
            }
        }
    }

    /**
     * @return bool
     */
    public function isError(): bool
    {
        return $this->isError;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @return int
     */
    public function getHttpCode()
    {
        return $this->httpCode;
    }

    /**
     * @return array
     */
    public function getRawData()
    {
        return $this->rawData;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
}
