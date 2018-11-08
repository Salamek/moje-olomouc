<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Operation;

use Salamek\MojeOlomouc\Request;
use Salamek\MojeOlomouc\Response;
use Salamek\MojeOlomouc\Model\ImportantMessage as ImportantMessageModel;

/**
 * Class ImportantMessage
 * @package Salamek\MojeOlomouc\Operation
 */
class ImportantMessage implements IOperation
{
    /** @var Request */
    private $request;

    /**
     * ImportantMessage constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return Response
     * @TODO filters
     * @TODO is there getter for all important-messages ?
     */
    public function getAll(): Response
    {
        return $this->request->get('/api/export/important-messages');
    }

    /**
     * @param ImportantMessageModel $importantMessage
     * @return Response
     */
    public function create(
        ImportantMessageModel $importantMessage
    ): Response
    {
        $data = [
            'importantMessage' => $importantMessage->toPrimitiveArray()
        ];

        return $this->request->create('/api/import/important-messages', $data);
    }

    /**
     * @param ImportantMessageModel $importantMessage
     * @param int|null $id
     * @return Response
     */
    public function update(
        ImportantMessageModel $importantMessage,
        int $id = null
    ): Response
    {
        $id = (is_null($id) ? $importantMessage->getId() : $id);
        $data = [
            'importantMessage' => $importantMessage->toPrimitiveArray()
        ];

        return $this->request->update('/api/import/important-messages', $id, $data);
    }

    /**
     * @param ImportantMessageModel $importantMessage
     * @param int|null $id
     * @return Response
     */
    public function delete(ImportantMessageModel $importantMessage, int $id = null): Response
    {
        $id = (is_null($id) ? $importantMessage->getId() : $id);
        return $this->request->delete('/api/import/important-messages', $id);
    }
}