<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Model;

/**
 * Interface IEntityImage
 * @package Salamek\MojeOlomouc\Model
 */
interface IEntityImage extends IModel
{
    /**
     * @param string|null $title
     */
    public function setTitle(string $title = null): void;

    /**
     * @param string $imageUrl
     */
    public function setImageUrl(string $imageUrl): void;

    /**
     * @param int $contentType
     */
    public function setContentType(int $contentType): void;

    /**
     * @param bool $isFeatured
     */
    public function setIsFeatured(bool $isFeatured): void;

    /**
     * @return string
     */
    public function getTitle(): ?string;

    /**
     * @return string
     */
    public function getImageUrl(): string;

    /**
     * @return int
     */
    public function getContentType(): int;

    /**
     * @return bool
     */
    public function getIsFeatured(): bool;
}