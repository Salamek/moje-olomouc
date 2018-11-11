<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Operation;

use Salamek\MojeOlomouc\Request;
use Salamek\MojeOlomouc\Response;
use \Salamek\MojeOlomouc\Model\ArticleCategory as ArticleCategoryModel;

/**
 * Class ArticleCategory
 * @package Salamek\MojeOlomouc\Operation
 */
class ArticleCategory implements IOperation
{
    /** @var Request */
    private $request;

    /**
     * ArticleCategory constructor.
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

        return $this->request->get('/api/export/article-categories', $data); //@TODO HYDRATOR
    }

    /**
     * @param ArticleCategoryModel $articleCategory
     * @return Response
     */
    public function create(
        ArticleCategoryModel $articleCategory
    ): Response
    {
        $data = [
            'articleCategory' => $articleCategory->toPrimitiveArray()
        ];

        return $this->request->create('/api/import/article-categories', $data);
    }

    /**
     * @param ArticleCategoryModel $articleCategory
     * @param int|null $id
     * @return Response
     */
    public function update(
        ArticleCategoryModel $articleCategory,
        int $id = null
    ): Response
    {
        $id = (is_null($id) ? $articleCategory->getId() : $id);
        $data = [
            'articleCategory' => $articleCategory->toPrimitiveArray()
        ];

        return $this->request->update('/api/import/article-categories', $id, $data);
    }

    /**
     * @param ArticleCategoryModel $articleCategory
     * @param int|null $id
     * @return Response
     */
    public function delete(ArticleCategoryModel $articleCategory, int $id = null): Response
    {
        $id = (is_null($id) ? $articleCategory->getId() : $id);
        return $this->request->delete('/api/import/article-categories', $id);
    }
}