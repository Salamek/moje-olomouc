<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Operation;

use Salamek\MojeOlomouc\Enum\DateTime;
use Salamek\MojeOlomouc\Request;
use Salamek\MojeOlomouc\Response;
use \Salamek\MojeOlomouc\Model\IPlace;

/**
 * Class Places
 * @package Salamek\MojeOlomouc\Operation
 */
class Places implements IOperation
{
    /** @var Request */
    private $request;

    /** @var null|string */
    private $hydrator;

    /**
     * Places constructor.
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

        return $this->request->get('/api/export/places', $data, ['places' => $this->hydrator]);
    }

    /**
     * @param IPlace[] $places
     * @return Response
     */
    public function create(
        array $places
    ): Response
    {
        return $this->request->create('/api/import/places', $places, 'place');
    }

    /**
     * @param IPlace[] $places
     * @return Response
     */
    public function update(
        array $places
    ): Response
    {
        return $this->request->update('/api/import/places', $places, 'place');
    }

    /**
     * @param IPlace[] $places
     * @return Response
     */
    public function delete(
        array $places
    ): Response
    {
        return $this->request->delete('/api/import/places', $places, 'place');
    }
}