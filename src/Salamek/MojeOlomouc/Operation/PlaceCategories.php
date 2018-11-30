<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Operation;

use Salamek\MojeOlomouc\Enum\DateTime;
use Salamek\MojeOlomouc\Enum\PlaceCategorySourceEnum;
use Salamek\MojeOlomouc\Hydrator\IPlaceCategory;
use Salamek\MojeOlomouc\Request;
use Salamek\MojeOlomouc\Response;

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
     * @param IPlaceCategory $hydrator
     */
    public function __construct(Request $request, IPlaceCategory $hydrator)
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
        string $source = PlaceCategorySourceEnum::PUBLISHED
    ): Response
    {
        $data = [
            'from' => ($from ? $from->format(DateTime::A_ISO8601) : null),
            'deleted' => $deleted,
            'invisible' => $invisible,
            'withExtraFields' => $withExtraFields,
            'source' => $source
        ];

        return $this->request->get('/api/export/place-categories', $data, ['placeCategories' => $this->hydrator]);
    }

    /**
     * @param array $placeCategories
     * @return Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function create(
        array $placeCategories
    ): Response
    {
        return $this->request->create('/api/import/place-categories', $placeCategories, 'placeCategory', $this->hydrator);
    }

    /**
     * @param array $placeCategories
     * @return Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function update(
        array $placeCategories
    ): Response
    {
        return $this->request->update('/api/import/place-categories', $placeCategories, 'placeCategory', $this->hydrator);
    }

    /**
     * @param array $placeCategories
     * @return Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function delete(
        array $placeCategories
    ): Response
    {
        return $this->request->delete('/api/import/place-categories', $placeCategories, 'placeCategory', $this->hydrator);
    }
}