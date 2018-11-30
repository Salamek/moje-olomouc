<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Model;

/**
 * Interface IPlace
 * @package Salamek\MojeOlomouc\Model
 */
interface IPlace extends IModel
{
    /**
     * @param string $title
     */
    public function setTitle(string $title): void;

    /**
     * @param string $description
     */
    public function setDescription(string $description): void;

    /**
     * @param string $address
     */
    public function setAddress(string $address): void;

    /**
     * @param float $lat
     */
    public function setLat(float $lat): void;

    /**
     * @param float $lon
     */
    public function setLon(float $lon): void;

    /**
     * @param IIdentifier $category
     */
    public function setCategory(IIdentifier $category): void;

    /**
     * @param EntityImage[] $images
     */
    public function setImages(array $images): void;

    /**
     * @param string|null $attachmentUrl
     */
    public function setAttachmentUrl(string $attachmentUrl = null): void;

    /**
     * @param boolean|null $isVisible
     */
    public function setIsVisible(bool $isVisible = null): void;

    /**
     * @param int|null $approveState
     */
    public function setApproveState(int $approveState = null): void;

    /**
     * @return string
     */
    public function getTitle(): string;

    /**
     * @return string
     */
    public function getDescription(): string;

    /**
     * @return string
     */
    public function getAddress(): string;

    /**
     * @return float
     */
    public function getLat(): float;

    /**
     * @return float
     */
    public function getLon(): float;

    /**
     * @return IIdentifier
     */
    public function getCategory(): IIdentifier;

    /**
     * @return EntityImage[]
     */
    public function getImages(): array;

    /**
     * @return string|null
     */
    public function getAttachmentUrl(): ?string;

    /**
     * @return boolean|null
     */
    public function getIsVisible(): ?bool;

    /**
     * @return int|null
     */
    public function getApproveState(): ?int;

}