<?php

declare(strict_types=1);

namespace Salamek\MojeOlomouc;

use Psr\Http\Message\ResponseInterface;

/**
 * Class Response
 * @package Salamek\MojeOlomouc
 * @TODO THIS NEEDS MORE WORK
 * @TODO I DONT LIKE ERROR HANDLING HERE
 */
class Response
{
    /** @var bool */
    private $isError;

    /** @var int */
    private $httpCode;

    /** @var array */
    private $errors;

    /** @var object */
    private $data;

    /**
     * Response constructor.
     * @param ResponseInterface $response
     * @param array $errors
     */
    public function __construct(ResponseInterface $response, array $errors = [])
    {
        $this->httpCode = $response->getStatusCode();
        $this->data = json_decode($response->getBody()->getContents());

        if ($this->data->isError)
        {
            $this->isError = true;
            $errors[] = [ //@TODO convert to obj or data to array ?
                'message' => $this->data->message,
                'code' => $this->data->code
            ];
        }
        else
        {
            $this->isError = false;
        }

        if (!$this->isError)
        {
            $this->isError = !empty($errors);
        }

        $this->errors = $errors;
    }

    /**
     * @return bool
     */
    public function isError(): bool
    {
        return $this->isError;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @return object
     */
    public function getData(): object
    {
        return $this->data;
    }
}
