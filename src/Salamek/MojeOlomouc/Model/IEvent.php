<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Model;

/**
 * Interface IEvent
 * @package Salamek\MojeOlomouc\Model
 */
interface IEvent extends IModel
{
    /**
     * @param string $title
     */
    public function setTitle(string  $title): void;

    /**
     * @param string $description
     */
    public function setDescription(string $description): void;

    /**
     * @param \DateTimeInterface $startAt
     */
    public function setStartAt(\DateTimeInterface $startAt): void;

    /**
     * @param \DateTimeInterface $endAt
     */
    public function setEndAt(\DateTimeInterface $endAt): void;

    /**
     * @param string $placeDesc
     */
    public function setPlaceDesc(string $placeDesc): void;

    /**
     * @param string $placeLat
     */
    public function setPlaceLat(string $placeLat): void;

    /**
     * @param string $placeLon
     */
    public function setPlaceLon(string $placeLon): void;

    /**
     * @param array $categoryIdsArr
     */
    public function setCategoryIdsArr(array $categoryIdsArr): void;

    /**
     * @param array $images
     */
    public function setImages(array $images): void;

    /**
     * @param string|null $attachmentUrl
     */
    public function setAttachmentUrl(string $attachmentUrl = null): void;

    /**
     * @param string|null $fee
     */
    public function setFee(string $fee = null): void;

    /**
     * @param string|null $webUrl
     */
    public function setWebUrl(string $webUrl = null): void;

    /**
     * @param string|null $facebookUrl
     */
    public function setFacebookUrl(string $facebookUrl = null): void;

    /**
     * @param int|null $consumerFlags
     */
    public function setConsumerFlags(int $consumerFlags = null): void;

    /**
     * @param bool|null $isVisible
     */
    public function setIsVisible(bool $isVisible = null): void;

    /**
     * @param int|null $approveState
     */
    public function setApproveState(int $approveState = null): void;

    /**
     * @param int|null $featuredLevel
     */
    public function setFeaturedLevel(int $featuredLevel = null): void;
    
    /**
     * @return string
     */
    public function getTitle(): string;

    /**
     * @return string
     */
    public function getDescription(): string;

    /**
     * @return \DateTimeInterface
     */
    public function getStartAt(): \DateTimeInterface;

    /**
     * @return \DateTimeInterface
     */
    public function getEndAt(): \DateTimeInterface;

    /**
     * @return string
     */
    public function getPlaceDesc(): string;

    /**
     * @return string
     */
    public function getPlaceLat(): string;

    /**
     * @return string
     */
    public function getPlaceLon(): string;

    /**
     * @return array
     */
    public function getCategoryIdsArr(): array;

    /**
     * @return array
     */
    public function getImages(): array;

    /**
     * @return string|null
     */
    public function getAttachmentUrl(): ?string;

    /**
     * @return string|null
     */
    public function getFee(): ?string;

    /**
     * @return string|null
     */
    public function getWebUrl(): ?string;

    /**
     * @return string|null
     */
    public function getFacebookUrl(): ?string;

    /**
     * @return int|null
     */
    public function getConsumerFlags(): ?int;

    /**
     * @return bool|null
     */
    public function getIsVisible(): ?bool;

    /**
     * @return int|null
     */
    public function getApproveState(): ?int;

    /**
     * @return int|null
     */
    public function getFeaturedLevel(): ?int;
}