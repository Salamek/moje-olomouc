<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Hydrator;


/**
 * Class ArticleCategory
 * @package Salamek\MojeOlomouc\Model
 */
class ArticleCategory implements IArticleCategory
{
    /**
     * @var string
     */
    private $modelClass;

    /**
     * ArticleCategory constructor.
     * @param string $modelClass
     */
    public function __construct(string $modelClass)
    {
        $this->modelClass = $modelClass;
    }

    /**
     * @param \Salamek\MojeOlomouc\Model\IArticleCategory $articleCategory
     * @return array
     */
    public function toPrimitiveArray(\Salamek\MojeOlomouc\Model\IArticleCategory $articleCategory): array
    {
        // Required
        $primitiveArray = [
            'title' => $articleCategory->getTitle(),
        ];

        // Optional
        if (!is_null($articleCategory->getConsumerFlags())) $primitiveArray['consumerFlags'] = $articleCategory->getConsumerFlags();
        if (!is_null($articleCategory->getIsImportant())) $primitiveArray['isImportant'] = $articleCategory->getIsImportant();
        if (!is_null($articleCategory->getIsVisible())) $primitiveArray['isVisible'] = $articleCategory->getIsVisible();

        return $primitiveArray;
    }

    /**
     * @param array $modelData
     * @return \Salamek\MojeOlomouc\Model\IArticleCategory
     */
    public function fromPrimitiveArray(array $modelData): \Salamek\MojeOlomouc\Model\IArticleCategory
    {
        return new $this->modelClass(
            $modelData['title'],
            (array_key_exists('consumerFlags', $modelData) ? $modelData['consumerFlags']: null),
            (array_key_exists('isImportant', $modelData) ? $modelData['isImportant']: null),
            (array_key_exists('isVisible', $modelData) ? $modelData['isVisible']: null),
            (array_key_exists('id', $modelData) ? $modelData['id']: null)
        );
    }
}