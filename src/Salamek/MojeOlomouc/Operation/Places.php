<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Operation;

use Salamek\MojeOlomouc\Exception\InvalidArgumentException;
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
            'fromUpdatedAt' => ($fromUpdatedAt ? $fromUpdatedAt->format(\DateTime::ISO8601) : null),
            'showDeleted' => $showDeleted,
            'onlyApproved' => $onlyApproved,
            'onlyVisible' => $onlyVisible,
            'extraFields' => $extraFields,
        ];

        return $this->request->get('/api/export/places', $data, ['places' => $this->hydrator]);
    }

    /**
     * @param IPlace $place
     * @return Response
     */
    public function create(
        IPlace $place
    ): Response
    {
        $data = [
            'place' => $place->toPrimitiveArray()
        ];

        return $this->request->create('/api/import/places', $data);
    }

    /**
     * @param IPlace $place
     * @param int|null $id
     * @return Response
     */
    public function update(
        IPlace $place,
        int $id = null
    ): Response
    {
        $id = (is_null($id) ? $place->getId() : $id);
        $data = [
            'place' => $place->toPrimitiveArray()
        ];

        return $this->request->update('/api/import/places', $id, $data);
    }

    /**
     * @param IPlace|null $place
     * @param int|null $id
     * @return Response
     */
    public function delete(IPlace $place = null, int $id = null): Response
    {
        if (is_null($place) && is_null($id))
        {
            throw new InvalidArgumentException('arguments $place or $id must be provided');
        }
        $id = (is_null($id) ? $place->getId() : $id);

        if (is_null($id))
        {
            throw new InvalidArgumentException('$id is not set');
        }
        return $this->request->delete('/api/import/places', $id);
    }
}