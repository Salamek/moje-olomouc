<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Operation;

use Salamek\MojeOlomouc\Request;
use Salamek\MojeOlomouc\Response;
use \Salamek\MojeOlomouc\Model\Article as ArticleModel;

/**
 * Class Article
 * @package Salamek\MojeOlomouc\Operation
 */
class Article implements IOperation
{
    /** @var Request */
    private $request;

    /**
     * Article constructor.
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

        return $this->request->get('/api/export/articles', $data); //@TODO HYDRATOR
    }

    /**
     * @param ArticleModel $article
     * @return Response
     */
    public function create(
        ArticleModel $article
    ): Response
    {
        $data = [
            'article' => $article->toPrimitiveArray()
        ];

        return $this->request->create('/api/import/articles', $data);
    }

    /**
     * @param ArticleModel $article
     * @param int|null $id
     * @return Response
     */
    public function update(
        ArticleModel $article,
        int $id = null
    ): Response
    {
        $id = (is_null($id) ? $article->getId() : $id);
        $data = [
            'article' => $article->toPrimitiveArray()
        ];

        return $this->request->update('/api/import/articles', $id, $data);
    }

    /**
     * @param ArticleModel $article
     * @param int|null $id
     * @return Response
     */
    public function delete(ArticleModel $article, int $id = null): Response
    {
        $id = (is_null($id) ? $article->getId() : $id);
        return $this->request->delete('/api/import/articles', $id);
    }
}