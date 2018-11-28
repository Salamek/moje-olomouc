<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Operation;

use Salamek\MojeOlomouc\Enum\ArticleCategorySourceEnum;
use Salamek\MojeOlomouc\Enum\DateTime;
use Salamek\MojeOlomouc\Hydrator\IArticleCategory;
use Salamek\MojeOlomouc\Request;
use Salamek\MojeOlomouc\Response;

/**
 * Class ArticleCategories
 * @package Salamek\MojeOlomouc\Operation
 */
class ArticleCategories implements IOperation
{
    /** @var Request */
    private $request;

    /** @var null|string */
    private $hydrator;
    
    /**
     * ArticleCategories constructor.
     * @param Request $request
     * @param IArticleCategory $hydrator
     */
    public function __construct(Request $request, IArticleCategory $hydrator)
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
        string $source = ArticleCategorySourceEnum::PUBLISHED
    ): Response
    {
        $data = [
            'from' => ($from ? $from->format(DateTime::A_ISO8601) : null),
            'deleted' => $deleted,
            'invisible' => $invisible,
            'withExtraFields' => $withExtraFields,
            'source' => $source
        ];

        return $this->request->get('/api/export/article-categories', $data, ['articleCategories' => $this->hydrator]);
    }

    /**
     * @param array $articleCategories
     * @return Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function create(
        array $articleCategories
    ): Response
    {
        return $this->request->create('/api/import/article-categories', $articleCategories, 'articleCategory', $this->hydrator);
    }

    /**
     * @param array $articleCategories
     * @return Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function update(
        array $articleCategories
    ): Response
    {

        return $this->request->update('/api/import/article-categories', $articleCategories, 'articleCategory', $this->hydrator);
    }

    /**
     * @param array|null $articleCategories
     * @return Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function delete(array $articleCategories = null): Response
    {
        return $this->request->delete('/api/import/article-categories', $articleCategories, 'articleCategory', $this->hydrator);
    }
}