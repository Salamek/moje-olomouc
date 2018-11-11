<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Operation;

use Salamek\MojeOlomouc\Request;
use Salamek\MojeOlomouc\Response;
use \Salamek\MojeOlomouc\Model\Event as EventModel;

/**
 * Class Event
 * @package Salamek\MojeOlomouc\Operation
 */
class Event implements IOperation
{
    /** @var Request */
    private $request;

    /**
     * Event constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param \DateTimeInterface|null $fromUpdatedAt
     * @param bool $showDeleted
     * @param bool $onlyApproved
     * @param bool $onlyVisible
     * @param bool $extraFields
     * @return Response
     */
    public function getAll(
        \DateTimeInterface $fromUpdatedAt = null,
        bool $showDeleted = false,
        bool $onlyApproved = true,
        bool $onlyVisible = true,
        bool $extraFields = false
    ): Response
    {
        $data = [
            'fromUpdatedAt' => ($fromUpdatedAt ? $fromUpdatedAt->format(\DateTime::ISO8601) : null),
            'showDeleted' => $showDeleted,
            'onlyApproved' => $onlyApproved,
            'onlyVisible' => $onlyVisible,
            'extraFields' => $extraFields,
        ];

        return $this->request->get('/api/export/events', $data); //@TODO HYDRATOR
    }

    /**
     * @param EventModel $event
     * @return Response
     */
    public function create(
        EventModel $event
    ): Response
    {
        $data = [
            'event' => $event->toPrimitiveArray()
        ];

        return $this->request->create('/api/import/events', $data);
    }

    /**
     * @param EventModel $event
     * @param int|null $id
     * @return Response
     */
    public function update(
        EventModel $event,
        int $id = null
    ): Response
    {
        $id = (is_null($id) ? $event->getId() : $id);
        $data = [
            'event' => $event->toPrimitiveArray()
        ];

        return $this->request->update('/api/import/events', $id, $data);
    }

    /**
     * @param EventModel $event
     * @param int|null $id
     * @return Response
     */
    public function delete(EventModel $event, int $id = null): Response
    {
        $id = (is_null($id) ? $event->getId() : $id);
        return $this->request->delete('/api/import/events', $id);
    }
}