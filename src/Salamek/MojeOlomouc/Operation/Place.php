<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Operation;

use Salamek\MojeOlomouc\Request;
use Salamek\MojeOlomouc\Response;
use \Salamek\MojeOlomouc\Model\Place as PlaceModel;

/**
 * Class Place
 * @package Salamek\MojeOlomouc\Operation
 */
class Place implements IOperation
{
    /** @var Request */
    private $request;

    /**
     * Place constructor.
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

        return $this->request->get('/api/export/places', $data); //@TODO HYDRATOR
    }

    /**
     * @param PlaceModel $place
     * @return Response
     */
    public function create(
        PlaceModel $place
    ): Response
    {
        $data = [
            'place' => $place->toPrimitiveArray()
        ];

        return $this->request->create('/api/import/places', $data);
    }

    /**
     * @param PlaceModel $place
     * @param int|null $id
     * @return Response
     */
    public function update(
        PlaceModel $place,
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
     * @param PlaceModel $place
     * @param int|null $id
     * @return Response
     */
    public function delete(PlaceModel $place, int $id = null): Response
    {
        $id = (is_null($id) ? $place->getId() : $id);
        return $this->request->delete('/api/import/places', $id);
    }
}