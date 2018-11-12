<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Operation;

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

        return $this->request->get('/api/export/articles', $data, ['articles' => $this->hydrator]);
    }

    /**
     * @param IArticle $article
     * @return Response
     */
    public function create(
        IArticle $article
    ): Response
    {
        $data = [
            'article' => $article->toPrimitiveArray()
        ];

        return $this->request->create('/api/import/articles', $data);
    }

    /**
     * @param IArticle $article
     * @param int|null $id
     * @return Response
     */
    public function update(
        IArticle $article,
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
     * @param IArticle|null $article
     * @param int|null $id
     * @return Response
     */
    public function delete(IArticle $article = null, int $id = null): Response
    {
        if (is_null($article) && is_null($id))
        {
            throw new InvalidArgumentException('arguments $article or $id must be provided');
        }
        $id = (is_null($id) ? $article->getId() : $id);

        if (is_null($id))
        {
            throw new InvalidArgumentException('$id is not set');
        }
        return $this->request->delete('/api/import/articles', $id);
    }
}