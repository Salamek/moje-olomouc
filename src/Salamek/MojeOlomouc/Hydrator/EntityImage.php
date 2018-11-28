<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Hydrator;

use Salamek\MojeOlomouc\Enum\EntityImageContentTypeEnum;
use Salamek\MojeOlomouc\Validator\IntInArrayValidator;
use Salamek\MojeOlomouc\Validator\MaxLengthValidator;

/**
 * Class EntityImage
 * @package Salamek\MojeOlomouc\Model
 */
class EntityImage implements IEntityImage
{
    /**
     * @var string
     */
    private $modelClass;

    /**
     * EntityImage constructor.
     * @param string $modelClass
     */
    public function __construct(string $modelClass)
    {
        $this->modelClass = $modelClass;
    }

    /**
     * @param \Salamek\MojeOlomouc\Model\IEntityImage $entityImage
     * @return array
     */
    public function toPrimitiveArray(\Salamek\MojeOlomouc\Model\IEntityImage $entityImage): array
    {
        return [
            'title' => $entityImage->getTitle(),
            'imageUrl' => $entityImage->getImageUrl(),
            'contentType' => $entityImage->getContentType(),
            'isFeatured' => $entityImage->getIsFeatured()
        ];
    }

    /**
     * @param array $modelData
     * @return \Salamek\MojeOlomouc\Model\IEntityImage
     */
    public function fromPrimitiveArray(array $modelData): \Salamek\MojeOlomouc\Model\IEntityImage
    {
        return new $this->modelClass(
            $modelData['imageUrl'],
            (array_key_exists('contentType', $modelData) ? $modelData['contentType'] : EntityImageContentTypeEnum::GRAPHICS_POSTER),
            (array_key_exists('title', $modelData) ? $modelData['title'] : null),
            (array_key_exists('isFeatured', $modelData) ? $modelData['isFeatured'] : false),
            (array_key_exists('id', $modelData) ? $modelData['id'] : null)
        );
    }
}