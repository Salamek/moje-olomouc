<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Operation;

use Salamek\MojeOlomouc\Enum\ArticleSourceEnum;
use Salamek\MojeOlomouc\Enum\DateTime;
use Salamek\MojeOlomouc\Hydrator\IArticle;
use Salamek\MojeOlomouc\Request;
use Salamek\MojeOlomouc\Response;

/**
 * Class Articles
 * @package Salamek\MojeOlomouc\Operation
 */
class Articles implements IOperation
{
    /** @var Request */
    private $request;

    /** @var null|string */
    private $hydrator;

    /**
     * Articles constructor.
     * @param Request $request
     * @param IArticle $hydrator
     */
    public function __construct(Request $request, IArticle $hydrator)
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
        string $source = ArticleSourceEnum::PUBLISHED,
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

        return $this->request->get('/api/export/articles', $data, ['articles' => $this->hydrator]);
    }

    /**
     * @param array $articles
     * @return Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function create(
        array $articles
    ): Response
    {
        return $this->request->create('/api/import/articles', $articles, 'article', $this->hydrator);
    }

    /**
     * @param array $articles
     * @return Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function update(
        array $articles
    ): Response
    {
        return $this->request->update('/api/import/articles', $articles, 'article', $this->hydrator);
    }

    /**
     * @param array $articles
     * @return Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function delete(array $articles): Response
    {
        return $this->request->delete('/api/import/articles', $articles, 'article', $this->hydrator);
    }
}