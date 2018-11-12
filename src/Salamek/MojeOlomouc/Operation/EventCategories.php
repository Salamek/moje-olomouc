<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Operation;

use Salamek\MojeOlomouc\Exception\InvalidArgumentException;
use Salamek\MojeOlomouc\Request;
use Salamek\MojeOlomouc\Response;
use \Salamek\MojeOlomouc\Model\IEventCategory;

/**
 * Class EventCategory
 * @package Salamek\MojeOlomouc\Operation
 */
class EventCategories implements IOperation
{
    /** @var Request */
    private $request;

    /** @var null|string */
    private $hydrator;

    /**
     * EventCategories constructor.
     * @param Request $request
     * @param string|null $hydrator
     */
    public function __construct(Request $request, string $hydrator = null)
    {
        $this->request = $request;
        $this->hydrator = $hydrator;
    }

    /**
     * @param \DateTimeInterface|null $fromUpdatedAt
     * @param bool $showDeleted
     * @param bool $onlyVisible
     * @param bool $extraFields
     * @return Response
     */
    public function getAll(
        \DateTimeInterface $fromUpdatedAt = null,
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

        return $this->request->get('/api/export/event-categories', $data, ['eventCategories' => $this->hydrator]);
    }

    /**
     * @param IEventCategory $eventCategory
     * @return Response
     */
    public function create(
        IEventCategory $eventCategory
    ): Response
    {
        $data = [
            'eventCategory' => $eventCategory->toPrimitiveArray()
        ];

        return $this->request->create('/api/import/event-categories', $data);
    }

    /**
     * @param IEventCategory $eventCategory
     * @param int|null $id
     * @return Response
     */
    public function update(
        IEventCategory $eventCategory,
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
     * @param IEventCategory|null $eventCategory
     * @param int|null $id
     * @return Response
     */
    public function delete(IEventCategory $eventCategory = null, int $id = null): Response
    {
        if (is_null($eventCategory) && is_null($id))
        {
            throw new InvalidArgumentException('arguments $articleCategory or $id must be provided');
        }
        $id = (is_null($id) ? $eventCategory->getId() : $id);

        if (is_null($id))
        {
            throw new InvalidArgumentException('$id is not set');
        }
        return $this->request->delete('/api/import/event-categories', $id);
    }
}