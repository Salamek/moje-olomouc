<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Operation;

use Salamek\MojeOlomouc\Request;
use Salamek\MojeOlomouc\Response;
use \Salamek\MojeOlomouc\Model\EventCategory as EventCategoryModel;

/**
 * Class EventCategory
 * @package Salamek\MojeOlomouc\Operation
 */
class EventCategory implements IOperation
{
    /** @var Request */
    private $request;

    /**
     * EventCategory constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param \DateTime|null $fromUpdatedAt
     * @param bool $showDeleted
     * @param bool $onlyVisible
     * @param bool $extraFields
     * @return Response
     */
    public function getAll(
        \DateTime $fromUpdatedAt = null,
        bool $showDeleted = false,
        bool $onlyVisible = true,
        bool $extraFields = false
    ): Response
    {
        $data = [
            'fromUpdatedAt' => ($fromUpdatedAt ? $fromUpdatedAt->format(\DateTime::ISO8601) : null),
            'showDeleted' => $showDeleted,
            'onlyVisible' => $onlyVisible,
            'extraFields' => $extraFields,
        ];

        return $this->request->get('/api/export/event-categories', $data); //@TODO HYDRATOR
    }

    /**
     * @param EventCategoryModel $eventCategory
     * @return Response
     */
    public function create(
        EventCategoryModel $eventCategory
    ): Response
    {
        $data = [
            'eventCategory' => $eventCategory->toPrimitiveArray()
        ];

        return $this->request->create('/api/import/event-categories', $data);
    }

    /**
     * @param EventCategoryModel $eventCategory
     * @param int|null $id
     * @return Response
     */
    public function update(
        EventCategoryModel $eventCategory,
        int $id = null
    ): Response
    {
        $id = (is_null($id) ? $eventCategory->getId() : $id);
        $data = [
            'eventCategory' => $eventCategory->toPrimitiveArray()
        ];

        return $this->request->update('/api/import/event-categories', $id, $data);
    }

    /**
     * @param EventCategoryModel $eventCategory
     * @param int|null $id
     * @return Response
     */
    public function delete(EventCategoryModel $eventCategory, int $id = null): Response
    {
        $id = (is_null($id) ? $eventCategory->getId() : $id);
        return $this->request->delete('/api/import/event-categories', $id);
    }
}