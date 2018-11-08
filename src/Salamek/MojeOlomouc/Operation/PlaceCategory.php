<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Operation;

use Salamek\MojeOlomouc\Request;
use Salamek\MojeOlomouc\Response;
use \Salamek\MojeOlomouc\Model\PlaceCategory as PlaceCategoryModel;

/**
 * Class PlaceCategory
 * @package Salamek\MojeOlomouc\Operation
 */
class PlaceCategory implements IOperation
{
    /** @var Request */
    private $request;

    /**
     * PlaceCategory constructor.
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

        return $this->request->get('/api/export/place-categories', $data); //@TODO HYDRATOR
    }

    /**
     * @param PlaceCategoryModel $placeCategory
     * @return Response
     */
    public function create(
        PlaceCategoryModel $placeCategory
    ): Response
    {
        $data = [
            'placeCategory' => $placeCategory->toPrimitiveArray()
        ];

        return $this->request->create('/api/import/place-categories', $data);
    }

    /**
     * @param PlaceCategoryModel $placeCategory
     * @param int|null $id
     * @return Response
     */
    public function update(
        PlaceCategoryModel $placeCategory,
        int $id = null
    ): Response
    {
        $id = (is_null($id) ? $placeCategory->getId() : $id);
        $data = [
            'placeCategory' => $placeCategory->toPrimitiveArray()
        ];

        return $this->request->update('/api/import/place-categories', $id, $data);
    }

    /**
     * @param PlaceCategoryModel $placeCategory
     * @param int|null $id
     * @return Response
     */
    public function delete(PlaceCategoryModel $placeCategory, int $id = null): Response
    {
        $id = (is_null($id) ? $placeCategory->getId() : $id);
        return $this->request->delete('/api/import/place-categories', $id);
    }
}