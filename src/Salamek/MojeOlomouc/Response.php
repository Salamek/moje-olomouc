<?php

declare(strict_types=1);

namespace Salamek\MojeOlomouc;

use Psr\Http\Message\ResponseInterface;
use Salamek\MojeOlomouc\Exception\InvalidJsonResponseException;

/**
 * Class Response
 * @package Salamek\MojeOlomouc
 */
class Response
{
    /** @var bool */
    private $isError;

    /** @var int */
    private $httpCode;

    /** @var  */
    private $rawData;

    /** @var object */
    private $data;

    /** @var int */
    private $code;

    /** @var string */
    private $message;

    /**
     * Response constructor.
     * @param ResponseInterface $response
     * @param array $hydratorMapping
     */
    public function __construct(ResponseInterface $response, array $hydratorMapping = [])
    {
        $this->httpCode = $response->getStatusCode();
        $rawContents = $response->getBody()->getContents();
        $this->rawData = json_decode($rawContents);
        if (!$this->rawData){
            throw new InvalidJsonResponseException(sprintf('getContents returned malformed or none JSON string (%s)', $rawContents));
        }

        if (!isset($this->rawData->isError))
        {
            throw new InvalidJsonResponseException(sprintf('isError is missing in JSON data (%s)', $rawContents));
        }

        if (!isset($this->rawData->message))
        {
            throw new InvalidJsonResponseException(sprintf('message is missing in JSON data (%s)', $rawContents));
        }

        if (!isset($this->rawData->code))
        {
            throw new InvalidJsonResponseException(sprintf('code is missing in JSON data (%s)', $rawContents));
        }

        if (!isset($this->rawData->data))
        {
            throw new InvalidJsonResponseException(sprintf('data is missing in JSON data (%s)', $rawContents));
        }

        $this->isError = $this->rawData->isError;
        $this->message = $this->rawData->message;
        $this->code = $this->rawData->code;
        $this->data = $this->rawData->data;

        //Use hydrator to modify data
        foreach($hydratorMapping AS $itemKey => $hydrator)
        {

            if (isset($this->data->{$itemKey}))
            {
                $hydratedItems = [];
                foreach ($this->data->{$itemKey} AS $item)
                {
                    $hydratedItems[] = call_user_func(sprintf('%s::fromPrimitiveArray', $hydrator), (array)$item);
                }

                $this->data->{$itemKey} = $hydratedItems;
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
     * @return object
     */
    public function getRawData()
    {
        return $this->rawData;
    }

    /**
     * @return object
     */
    public function getData()
    {
        return $this->data;
    }
}
