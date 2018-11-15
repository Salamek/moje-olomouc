<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Operation;

use Salamek\MojeOlomouc\Enum\DateTime;
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

    /** @var null|string */
    private $hydrator;

    /**
     * PlaceCategories constructor.
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
            'fromUpdatedAt' => ($fromUpdatedAt ? $fromUpdatedAt->format(DateTime::NOT_A_ISO8601) : null),
            'showDeleted' => $showDeleted,
            'onlyVisible' => $onlyVisible,
            'extraFields' => $extraFields,
        ];

        return $this->request->get('/api/export/place-categories', $data, ['placeCategories' => $this->hydrator]);
    }

    /**
     * @param IPlaceCategory[] $placeCategories
     * @return Response
     */
    public function create(
        array $placeCategories
    ): Response
    {
        return $this->request->create('/api/import/place-categories', $placeCategories, 'placeCategory');
    }

    /**
     * @param IPlaceCategory[] $placeCategories
     * @return Response
     */
    public function update(
        array $placeCategories
    ): Response
    {
        return $this->request->update('/api/import/place-categories', $placeCategories, 'placeCategory');
    }

    /**
     * @param IPlaceCategory[] $placeCategories
     * @return Response
     */
    public function delete(
        array $placeCategories
    ): Response
    {
        return $this->request->delete('/api/import/place-categories', $placeCategories, 'placeCategory');
    }
}