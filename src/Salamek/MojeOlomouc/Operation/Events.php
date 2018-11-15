<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Operation;

use Salamek\MojeOlomouc\Enum\DateTime;
use Salamek\MojeOlomouc\Exception\InvalidArgumentException;
use Salamek\MojeOlomouc\Request;
use Salamek\MojeOlomouc\Response;
use \Salamek\MojeOlomouc\Model\IEvent;

/**
 * Class Events
 * @package Salamek\MojeOlomouc\Operation
 */
class Events implements IOperation
{
    /** @var Request */
    private $request;

    /** @var null|string */
    private $hydrator;

    /**
     * Events constructor.
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
            'fromUpdatedAt' => ($fromUpdatedAt ? $fromUpdatedAt->format(DateTime::NOT_A_ISO8601) : null),
            'showDeleted' => $showDeleted,
            'onlyApproved' => $onlyApproved,
            'onlyVisible' => $onlyVisible,
            'extraFields' => $extraFields,
        ];

        return $this->request->get('/api/export/events', $data, ['events' => $this->hydrator]);
    }

    /**
     * @param IEvent[] $events
     * @return Response
     */
    public function create(
        array $events
    ): Response
    {
        return $this->request->create('/api/import/events', $events, 'event');
    }

    /**
     * @param IEvent[] $events
     * @return Response
     */
    public function update(
        array $events
    ): Response
    {
        return $this->request->update('/api/import/events', $events, 'event');
    }

    /**
     * @param IEvent[] $events
     * @return Response
     */
    public function delete(array $events): Response
    {
        return $this->request->delete('/api/import/events', $events, 'event');
    }
}