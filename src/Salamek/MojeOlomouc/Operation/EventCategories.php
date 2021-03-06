<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Operation;

use Salamek\MojeOlomouc\Enum\DateTime;
use Salamek\MojeOlomouc\Enum\EventCategorySourceEnum;
use Salamek\MojeOlomouc\Hydrator\IEventCategory;
use Salamek\MojeOlomouc\Request;
use Salamek\MojeOlomouc\Response;

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
     * @param IEventCategory $hydrator
     */
    public function __construct(Request $request, IEventCategory $hydrator)
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
     * @return Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getAll(
        \DateTimeInterface $from = null,
        bool $deleted = false,
        bool $invisible = false,
        bool $withExtraFields = false,
        string $source = EventCategorySourceEnum::PUBLISHED
    ): Response
    {
        $data = [
            'from' => ($from ? $from->format(DateTime::A_ISO8601) : null),
            'deleted' => $deleted,
            'invisible' => $invisible,
            'withExtraFields' => $withExtraFields,
            'source' => $source
        ];

        return $this->request->get('/api/export/event-categories', $data, ['eventCategories' => $this->hydrator]);
    }

    /**
     * @param array $eventCategories
     * @return Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function create(
        array $eventCategories
    ): Response
    {
        return $this->request->create('/api/import/event-categories', $eventCategories, 'eventCategory', $this->hydrator);
    }

    /**
     * @param array $eventCategories
     * @return Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function update(
        array $eventCategories
    ): Response
    {
        return $this->request->update('/api/import/event-categories', $eventCategories, 'eventCategory', $this->hydrator);
    }

    /**
     * @param array $eventCategories
     * @return Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function delete(array $eventCategories): Response
    {
        return $this->request->delete('/api/import/event-categories', $eventCategories, 'eventCategory', $this->hydrator);
    }
}