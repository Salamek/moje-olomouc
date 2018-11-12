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
     * @param IImportantMessage $importantMessage
     * @return Response
     */
    public function create(
        IImportantMessage $importantMessage
    ): Response
    {
        $data = [
            'importantMessage' => $importantMessage->toPrimitiveArray()
        ];

        return $this->request->create('/api/import/important-messages', $data);
    }

    /**
     * @param IImportantMessage $importantMessage
     * @param int|null $id
     * @return Response
     */
    public function update(
        IImportantMessage $importantMessage,
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
     * @param IImportantMessage|null $importantMessage
     * @param int|null $id
     * @return Response
     */
    public function delete(IImportantMessage $importantMessage = null, int $id = null): Response
    {
        if (is_null($importantMessage) && is_null($id))
        {
            throw new InvalidArgumentException('arguments $importantMessage or $id must be provided');
        }
        $id = (is_null($id) ? $importantMessage->getId() : $id);

        if (is_null($id))
        {
            throw new InvalidArgumentException('$id is not set');
        }
        return $this->request->delete('/api/import/important-messages', $id);
    }
}