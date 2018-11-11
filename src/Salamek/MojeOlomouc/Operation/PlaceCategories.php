<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Operation;

use Salamek\MojeOlomouc\Exception\InvalidArgumentException;
use Salamek\MojeOlomouc\Request;
use Salamek\MojeOlomouc\Response;
use \Salamek\MojeOlomouc\Model\IPlaceCategory;

/**
 * Class PlaceCategories
 * @package Salamek\MojeOlomouc\Operation
 */
class PlaceCategories implements IOperation
{
    /** @var Request */
    private $request;

    /**
     * PlaceCategories constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
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

        return $this->request->get('/api/export/place-categories', $data); //@TODO HYDRATOR
    }

    /**
     * @param IPlaceCategory $placeCategory
     * @return Response
     */
    public function create(
        IPlaceCategory $placeCategory
    ): Response
    {
        $data = [
            'placeCategory' => $placeCategory->toPrimitiveArray()
        ];

        return $this->request->create('/api/import/place-categories', $data);
    }

    /**
     * @param IPlaceCategory $placeCategory
     * @param int|null $id
     * @return Response
     */
    public function update(
        IPlaceCategory $placeCategory,
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
     * @param IPlaceCategory|null $placeCategory
     * @param int|null $id
     * @return Response
     */
    public function delete(IPlaceCategory $placeCategory = null, int $id = null): Response
    {
        if (is_null($placeCategory) && is_null($id))
        {
            throw new InvalidArgumentException('arguments $placeCategory or $id must be provided');
        }
        $id = (is_null($id) ? $placeCategory->getId() : $id);

        if (is_null($id))
        {
            throw new InvalidArgumentException('$id is not set');
        }
        return $this->request->delete('/api/import/place-categories', $id);
    }
}