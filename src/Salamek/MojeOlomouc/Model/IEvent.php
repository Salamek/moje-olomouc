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
     * @param string $attachmentUrl
     */
    public function setAttachmentUrl(string $attachmentUrl): void;

    /**
     * @param string $fee
     */
    public function setFee(string $fee): void;

    /**
     * @param string $webUrl
     */
    public function setWebUrl(string $webUrl): void;

    /**
     * @param string $facebookUrl
     */
    public function setFacebookUrl(string $facebookUrl): void;

    /**
     * @param int $consumerFlags
     */
    public function setConsumerFlags(int $consumerFlags): void;

    /**
     * @param bool $isVisible
     */
    public function setIsVisible(bool $isVisible): void;

    /**
     * @param int $approveState
     */
    public function setApproveState(int $approveState): void;

    /**
     * @param int $featuredLevel
     */
    public function setFeaturedLevel(int $featuredLevel): void;
    
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
     * @return string
     */
    public function getAttachmentUrl(): ?string;

    /**
     * @return string
     */
    public function getFee(): ?string;

    /**
     * @return string
     */
    public function getWebUrl(): ?string;

    /**
     * @return string
     */
    public function getFacebookUrl(): ?string;

    /**
     * @return int
     */
    public function getConsumerFlags(): ?int;

    /**
     * @return bool
     */
    public function getIsVisible(): bool;

    /**
     * @return int
     */
    public function getApproveState(): ?int;

    /**
     * @return int
     */
    public function getFeaturedLevel(): ?int;
}