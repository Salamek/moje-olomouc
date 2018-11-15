<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Operation;

use Salamek\MojeOlomouc\Exception\InvalidArgumentException;
use Salamek\MojeOlomouc\Request;
use Salamek\MojeOlomouc\Response;
use Salamek\MojeOlomouc\Model\IImportantMessage;

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
     * @param string|null $hydrator
     */
    public function __construct(Request $request, string $hydrator = null)
    {
        $this->request = $request;
        $this->hydrator = $hydrator;
    }

    /**
     * @return Response
     * @TODO filters
     * @TODO is there getter for all important-messages ?
     */
    public function getAll(): Response
    {
        return $this->request->get('/api/export/important-messages', [], ['importantMessages' => $this->hydrator]);
    }

    /**
     * @param IImportantMessage[] $importantMessages
     * @return Response
     */
    public function create(
        array $importantMessages
    ): Response
    {
        return $this->request->create('/api/import/important-messages', $importantMessages, 'importantMessage');
    }

    /**
     * @param IImportantMessage[] $importantMessages
     * @return Response
     */
    public function update(
        array $importantMessages
    ): Response
    {
        return $this->request->update('/api/import/important-messages', $importantMessages, 'importantMessage');
    }

    /**
     * @param IImportantMessage[] $importantMessages
     * @return Response
     */
    public function delete(array $importantMessages): Response
    {
        return $this->request->delete('/api/import/important-messages', $importantMessages, 'importantMessage');
    }
}