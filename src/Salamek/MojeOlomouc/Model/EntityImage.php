<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Model;

use Salamek\MojeOlomouc\Enum\EntityImageContentTypeEnum;
use Salamek\MojeOlomouc\Validator\IntInArrayValidator;
use Salamek\MojeOlomouc\Validator\MaxLengthValidator;

/**
 * Class EntityImage
 * @package Salamek\MojeOlomouc\Model
 */
class EntityImage implements IEntityImage
{
    use TIdentifier;

    /** @var string|null */
    private $title;

    /** @var string */
    private $imageUrl;

    /** @var int */
    private $contentType;

    /** @var bool */
    private $isFeatured;

    /**
     * EntityImage constructor.
     * @param string $imageUrl
     * @param int $contentType
     * @param string|null $title
     * @param bool $isFeatured
     * @param int|null $id
     */
    public function __construct(string $imageUrl, int $contentType = EntityImageContentTypeEnum::GRAPHICS_POSTER, string $title = null, bool $isFeatured = false, int $id = null)
    {
        $this->setImageUrl($imageUrl);
        $this->setContentType($contentType);
        $this->setTitle($title);
        $this->setIsFeatured($isFeatured);
        $this->setId($id);
    }

    /**
     * @param string|null $title
     */
    public function setTitle(string $title = null): void
    {
        if (!is_null($title))
        {
            MaxLengthValidator::validate($title, 65535); // Not defined in docs. i assume TEXT col type
        }

        $this->title = $title;
    }

    /**
     * @param string $imageUrl
     */
    public function setImageUrl(string $imageUrl): void
    {
        MaxLengthValidator::validate($imageUrl, 65535); // Not defined in docs. i assume TEXT col type
        $this->imageUrl = $imageUrl;
    }

    /**
     * @param int $contentType
     */
    public function setContentType(int $contentType): void
    {
        IntInArrayValidator::validate($contentType, [
            EntityImageContentTypeEnum::GRAPHICS_POSTER,
            EntityImageContentTypeEnum::PICTURE,
        ]);
        $this->contentType = $contentType;
    }

    /**
     * @param bool $isFeatured
     */
    public function setIsFeatured(bool $isFeatured): void
    {
        $this->isFeatured = $isFeatured;
    }

    /**
     * @return string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }

    /**
     * @return int
     */
    public function getContentType(): int
    {
        return $this->contentType;
    }

    /**
     * @return bool
     */
    public function getIsFeatured(): bool
    {
        return $this->isFeatured;
    }

    /**
     * @return array
     */
    public function toPrimitiveArray(): array
    {
        return [
            'title' => $this->title,
            'imageUrl' => $this->imageUrl,
            'contentType' => $this->contentType,
            'isFeatured' => $this->isFeatured
        ];
    }

    /**
     * @param array $modelData
     * @return EntityImage
     */
    public static function fromPrimitiveArray(array $modelData): EntityImage
    {
        return new EntityImage(
            $modelData['imageUrl'],
            (array_key_exists('contentType', $modelData) ? $modelData['contentType'] : EntityImageContentTypeEnum::GRAPHICS_POSTER),
            (array_key_exists('title', $modelData) ? $modelData['title'] : null),
            (array_key_exists('isFeatured', $modelData) ? $modelData['isFeatured'] : false),
            (array_key_exists('id', $modelData) ? $modelData['id'] : null)
        );
    }
}