<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Operation;

use Salamek\MojeOlomouc\Hydrator\IImportantMessage;
use Salamek\MojeOlomouc\Request;
use Salamek\MojeOlomouc\Response;

/**
 * Class ImportantMessages
 * @package Salamek\MojeOlomouc\Operation
 */
class ImportantMessages implements IOperation
{
    /** @var Request */
    private $request;

    /** @var null|string */
    private $hydrator;

    /**
     * ImportantMessages constructor.
     * @param Request $request
     * @param IImportantMessage $hydrator
     */
    public function __construct(Request $request, IImportantMessage $hydrator)
    {
        $this->request = $request;
        $this->hydrator = $hydrator;
    }

    /**
     * @return Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @internal
     * @TODO
     */
    public function getAll(): Response
    {
        return $this->request->get('/api/export/important-messages', [], ['importantMessages' => $this->hydrator]);
    }

    /**
     * @param array $importantMessages
     * @return Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function create(
        array $importantMessages
    ): Response
    {
        return $this->request->create('/api/import/important-messages', $importantMessages, 'importantMessage', $this->hydrator);
    }

    /**
     * @param array $importantMessages
     * @return Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function update(
        array $importantMessages
    ): Response
    {
        return $this->request->update('/api/import/important-messages', $importantMessages, 'importantMessage', $this->hydrator);
    }

    /**
     * @param array $importantMessages
     * @return Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function delete(array $importantMessages): Response
    {
        return $this->request->delete('/api/import/important-messages', $importantMessages, 'importantMessage', $this->hydrator);
    }
}