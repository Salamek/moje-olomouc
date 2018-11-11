<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Model;

/**
 * Interface IArticle
 * @package Salamek\MojeOlomouc\Model
 */
interface IArticle extends IModel
{
    /**
     * @param string $title
     */
    public function setTitle(string $title): void;

    /**
     * @param string $content
     */
    public function setContent(string $content): void;

    /**
     * @param string $author
     */
    public function setAuthor(string $author): void;

    /**
     * @param int $categoryId
     */
    public function setCategoryId(int $categoryId): void;

    /**
     * @param \DateTimeInterface $dateTimeAt
     */
    public function setDateTimeAt(\DateTimeInterface $dateTimeAt): void;

    /**
     * @param EntityImage[] $images
     */
    public function setImages(array $images): void;

    /**
     * @param string $attachmentUrl
     */
    public function setAttachmentUrl(string $attachmentUrl): void;

    /**
     * @param boolean $isVisible
     */
    public function setIsVisible(bool $isVisible): void;

    /**
     * @param boolean $isImportant
     */
    public function setIsImportant(bool $isImportant): void;

    /**
     * @param int $approveState
     */
    public function setApproveState(int $approveState): void;
    
    /**
     * @return string
     */
    public function getTitle(): string;

    /**
     * @return string
     */
    public function getContent(): string;

    /**
     * @return string
     */
    public function getAuthor(): string;

    /**
     * @return int
     */
    public function getCategoryId(): int;

    /**
     * @return \DateTimeInterface
     */
    public function getDateTimeAt(): \DateTimeInterface;

    /**
     * @return EntityImage[]
     */
    public function getImages(): array;

    /**
     * @return string
     */
    public function getAttachmentUrl(): ?string;

    /**
     * @return boolean
     */
    public function getIsVisible(): bool;

    /**
     * @return boolean
     */
    public function getIsImportant(): bool;

    /**
     * @return int
     */
    public function getApproveState(): ?int;
}