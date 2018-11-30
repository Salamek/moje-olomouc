<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Hydrator;
use Salamek\MojeOlomouc\Enum\DateTime;


/**
 * Class Article
 * @package Salamek\MojeOlomouc\Model
 */
class Article implements IArticle
{
    /**
     * @var string
     */
    private $modelClass;

    /**
     * @var IEntityImage
     */
    private $entityImageHydrator;

    /**
     * Article constructor.
     * @param string $modelClass
     * @param IEntityImage $entityImageHydrator
     */
    public function __construct(string $modelClass, IEntityImage $entityImageHydrator)
    {
        $this->modelClass = $modelClass;
        $this->entityImageHydrator = $entityImageHydrator;
    }

    /**
     * @param \Salamek\MojeOlomouc\Model\IArticle $article
     * @return array
     */
    public function toPrimitiveArray(\Salamek\MojeOlomouc\Model\IArticle $article): array
    {
        $primitiveImages = [];
        foreach ($article->getImages() AS $image)
        {
            $primitiveImages[] = $this->entityImageHydrator->toPrimitiveArray($image);
        }

        $primitiveArray = [
            'title' => $article->getTitle(),
            'content' => $article->getContent(),
            'author' => $article->getAuthor(),
            'categoryId' => $article->getCategory()->getEntityIdentifier(),
            'dateTimeAt' => $article->getDateTimeAt()->format(DateTime::NOT_A_ISO8601),
            'images' => $primitiveImages
        ];

        if (!is_null($article->getAttachmentUrl())) $primitiveArray['attachmentUrl'] = $article->getAttachmentUrl();
        if (!is_null($article->getIsVisible())) $primitiveArray['isVisible'] = $article->getIsVisible();
        if (!is_null($article->getIsImportant())) $primitiveArray['isImportant'] = $article->getIsImportant();
        if (!is_null($article->getApproveState())) $primitiveArray['approveState'] = $article->getApproveState();

        return $primitiveArray;
    }

    /**
     * @param array $modelData
     * @return \Salamek\MojeOlomouc\Model\IArticle
     */
    public function fromPrimitiveArray(array $modelData): \Salamek\MojeOlomouc\Model\IArticle
    {
        $images = [];
        foreach($modelData['images'] AS $primitiveImage)
        {
            $images[] = $this->entityImageHydrator->fromPrimitiveArray($primitiveImage);
        }

        $dateTimeAt = \DateTime::createFromFormat(DateTime::NOT_A_ISO8601, $modelData['dateTimeAt']);

        return new $this->modelClass(
            $modelData['title'],
            $modelData['content'],
            $modelData['author'],
            new \Salamek\MojeOlomouc\Model\Identifier($modelData['categoryId']),
            $dateTimeAt,
            $images,
            (array_key_exists('attachmentUrl', $modelData) ? $modelData['attachmentUrl'] : null),
            (array_key_exists('isVisible', $modelData) ? $modelData['isVisible'] : null),
            (array_key_exists('isImportant', $modelData) ? $modelData['isImportant'] : null),
            (array_key_exists('approveState', $modelData) ? $modelData['approveState'] : null),
            (array_key_exists('id', $modelData) ? $modelData['id'] : null)
        );
    }
}