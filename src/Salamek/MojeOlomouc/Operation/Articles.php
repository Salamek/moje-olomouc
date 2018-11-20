<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Operation;

use Salamek\MojeOlomouc\Enum\ArticleSourceEnum;
use Salamek\MojeOlomouc\Enum\DateTime;
use Salamek\MojeOlomouc\Exception\InvalidArgumentException;
use Salamek\MojeOlomouc\Request;
use Salamek\MojeOlomouc\Response;
use \Salamek\MojeOlomouc\Model\IArticle;

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
     * @param string|null $hydrator
     */
    public function __construct(Request $request, string $hydrator = null)
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
     * @param IArticle[] $articles
     * @return Response
     */
    public function create(
        array $articles
    ): Response
    {
        return $this->request->create('/api/import/articles', $articles, 'article');
    }

    /**
     * @param IArticle[] $articles
     * @return Response
     */
    public function update(
        array $articles
    ): Response
    {
        return $this->request->update('/api/import/articles', $articles, 'article');
    }

    /**
     * @param IArticle[] $articles
     * @return Response
     */
    public function delete(array $articles): Response
    {
        return $this->request->delete('/api/import/articles', $articles, 'article');
    }
}