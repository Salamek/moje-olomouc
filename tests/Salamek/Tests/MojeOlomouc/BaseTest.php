<?php
declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc;


use GuzzleHttp\ClientInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

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
}