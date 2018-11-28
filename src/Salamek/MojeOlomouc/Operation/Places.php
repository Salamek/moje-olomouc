<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Operation;

use Salamek\MojeOlomouc\Enum\DateTime;
use Salamek\MojeOlomouc\Enum\PlaceSourceEnum;
use Salamek\MojeOlomouc\Hydrator\IPlace;
use Salamek\MojeOlomouc\Request;
use Salamek\MojeOlomouc\Response;

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
     * @param IPlace $hydrator
     */
    public function __construct(Request $request, IPlace $hydrator)
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
        string $source = PlaceSourceEnum::PUBLISHED,
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

        return $this->request->get('/api/export/places', $data, ['places' => $this->hydrator]);
    }

    /**
     * @param array $places
     * @return Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function create(
        array $places
    ): Response
    {
        return $this->request->create('/api/import/places', $places, 'place', $this->hydrator);
    }

    /**
     * @param array $places
     * @return Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function update(
        array $places
    ): Response
    {
        return $this->request->update('/api/import/places', $places, 'place', $this->hydrator);
    }

    /**
     * @param array $places
     * @return Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function delete(
        array $places
    ): Response
    {
        return $this->request->delete('/api/import/places', $places, 'place', $this->hydrator);
    }
}