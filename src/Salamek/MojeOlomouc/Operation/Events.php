<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Operation;

use Salamek\MojeOlomouc\Enum\DateTime;
use Salamek\MojeOlomouc\Enum\EventSourceEnum;
use Salamek\MojeOlomouc\Hydrator\IEvent;
use Salamek\MojeOlomouc\Request;
use Salamek\MojeOlomouc\Response;

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
     * @param IEvent $hydrator
     */
    public function __construct(Request $request, IEvent $hydrator)
    {
        $this->request = $request;
        $this->hydrator = $hydrator;
    }

    /**
     * @param \DateTimeInterface|null $from
     * @param bool $deleted
     * @param bool $invisible
     * @param bool $withExtraFields
     * @param string $source
     * @param bool $own
     * @return Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getAll(
        \DateTimeInterface $from = null,
        bool $deleted = false,
        bool $invisible = false,
        bool $withExtraFields = false,
        string $source = EventSourceEnum::PUBLISHED,
        bool $own = false
    ): Response
    {
        $data = [
            'from' => ($from ? $from->format(DateTime::A_ISO8601) : null),
            'deleted' => $deleted,
            'invisible' => $invisible,
            'withExtraFields' => $withExtraFields,
            'source' => $source,
            'own' => $own,
        ];

        return $this->request->get('/api/export/events', $data, ['events' => $this->hydrator]);
    }

    /**
     * @param array $events
     * @return Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function create(
        array $events
    ): Response
    {
        return $this->request->create('/api/import/events', $events, 'event', $this->hydrator);
    }

    /**
     * @param array $events
     * @return Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function update(
        array $events
    ): Response
    {
        return $this->request->update('/api/import/events', $events, 'event', $this->hydrator);
    }

    /**
     * @param array $events
     * @return Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function delete(array $events): Response
    {
        return $this->request->delete('/api/import/events', $events, 'event', $this->hydrator);
    }
}