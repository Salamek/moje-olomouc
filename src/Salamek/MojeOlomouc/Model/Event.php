<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Model;

use Salamek\MojeOlomouc\Exception\EventApproveStateEnum;
use Salamek\MojeOlomouc\Exception\EventFeaturedLevelEnum;
use Salamek\MojeOlomouc\Validator\GpsStringValidator;
use Salamek\MojeOlomouc\Validator\IntInArrayValidator;
use Salamek\MojeOlomouc\Validator\MaxLengthValidator;

/**
 * Class Event
 * @package Salamek\MojeOlomouc\Model
 */
class Event implements IEvent
{
    /** @var string */
    private $title;

    /** @var string */
    private $description;

    /** @var \DateTime */
    private $startAt;

    /** @var \DateTime */
    private $endAt;

    /** @var string */
    private $placeDesc;

    /** @var string */
    private $placeLat;

    /** @var string */
    private $placeLon;

    /** @var array */
    private $categoryIdsArr;

    /** @var EntityImage[] */
    private $images;

    /** @var string|null */
    private $attachmentUrl;

    /** @var string|null */
    private $fee;

    /** @var string|null */
    private $webUrl;

    /** @var string|null */
    private $facebookUrl;

    /** @var int|null */
    private $consumerFlags;

    /** @var bool */
    private $isVisible;

    /** @var int|null */
    private $approveState;

    /** @var int|null */
    private $featuredLevel;

    /**
     * Event constructor.
     * @param string $title
     * @param string $description
     * @param \DateTime $startAt
     * @param \DateTime $endAt
     * @param string $placeDesc
     * @param string $placeLat
     * @param string $placeLon
     * @param array $categoryIdsArr
     * @param IEntityImage[] $images
     * @param string|null $attachmentUrl
     * @param string|null $fee
     * @param string|null $webUrl
     * @param string|null $facebookUrl
     * @param int|null $consumerFlags
     * @param bool $isVisible
     * @param int|null $approveState
     * @param int|null $featuredLevel
     */
    public function __construct(
        string $title,
        string $description,
        \DateTime $startAt,
        \DateTime $endAt,
        string $placeDesc,
        string $placeLat,
        string $placeLon,
        array $categoryIdsArr,
        array $images = [],
        string $attachmentUrl = null,
        string $fee = null,
        string $webUrl = null,
        string $facebookUrl = null,
        int $consumerFlags = null,
        bool $isVisible = true,
        int $approveState = null,
        int $featuredLevel = null
    )
    {
        $this->setTitle($title);
        $this->setDescription($description);
        $this->setStartAt($startAt);
        $this->setEndAt($endAt);
        $this->setPlaceDesc($placeDesc);
        $this->setPlaceLat($placeLat);
        $this->setPlaceLon($placeLon);
        $this->setCategoryIdsArr($categoryIdsArr);
        $this->setImages($images);
        $this->setAttachmentUrl($attachmentUrl);
        $this->setFee($fee);
        $this->setWebUrl($webUrl);
        $this->setFacebookUrl($facebookUrl);
        $this->setConsumerFlags($consumerFlags);
        $this->setIsVisible($isVisible);
        $this->setApproveState($approveState);
        $this->setFeaturedLevel($featuredLevel);
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        MaxLengthValidator::validate($title, 150);
        $this->title = $title;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        MaxLengthValidator::validate($description, 65535); // Not defined in docs. i assume TEXT col type
        $this->description = $description;
    }

    /**
     * @param \DateTime $startAt
     */
    public function setStartAt(\DateTime $startAt): void
    {
        $this->startAt = $startAt;
    }

    /**
     * @param \DateTime $endAt
     */
    public function setEndAt(\DateTime $endAt): void
    {
        $this->endAt = $endAt;
    }

    /**
     * @param string $placeDesc
     */
    public function setPlaceDesc(string $placeDesc): void
    {
        MaxLengthValidator::validate($placeDesc, 300);
        $this->placeDesc = $placeDesc;
    }

    /**
     * @param string $placeLat
     */
    public function setPlaceLat(string $placeLat): void
    {
        GpsStringValidator::validate($placeLat);
        $this->placeLat = $placeLat;
    }

    /**
     * @param string $placeLon
     */
    public function setPlaceLon(string $placeLon): void
    {
        GpsStringValidator::validate($placeLon);
        $this->placeLon = $placeLon;
    }

    /**
     * @param array $categoryIdsArr
     */
    public function setCategoryIdsArr(array $categoryIdsArr): void
    {
        $this->categoryIdsArr = $categoryIdsArr;
    }

    /**
     * @param array $images
     */
    public function setImages(array $images): void
    {
        $this->images = $images;
    }

    /**
     * @param string|null $attachmentUrl
     */
    public function setAttachmentUrl(string $attachmentUrl = null): void
    {
        if (!is_null($attachmentUrl))
        {
            MaxLengthValidator::validate($attachmentUrl, 65535); // Not defined in docs. i assume TEXT col type
        }
        $this->attachmentUrl = $attachmentUrl;
    }

    /**
     * @param string|null $fee
     */
    public function setFee(string $fee = null): void
    {
        if (!is_null($fee))
        {
            MaxLengthValidator::validate($fee, 50);
        }
        $this->fee = $fee;
    }

    /**
     * @param string|null $webUrl
     */
    public function setWebUrl(string $webUrl = null): void
    {
        if (!is_null($webUrl))
        {
            MaxLengthValidator::validate($webUrl, 300);
        }
        $this->webUrl = $webUrl;
    }

    /**
     * @param string|null $facebookUrl
     */
    public function setFacebookUrl(string $facebookUrl = null): void
    {
        if (!is_null($facebookUrl))
        {
            MaxLengthValidator::validate($facebookUrl, 300);
        }
        $this->facebookUrl = $facebookUrl;
    }

    /**
     * @param int|null $consumerFlags
     */
    public function setConsumerFlags(int $consumerFlags = null): void
    {
        $this->consumerFlags = $consumerFlags;
    }

    /**
     * @param bool $isVisible
     */
    public function setIsVisible(bool $isVisible = true): void
    {
        $this->isVisible = $isVisible;
    }

    /**
     * @param int|null $approveState
     */
    public function setApproveState(int $approveState = null): void
    {
        if (!is_null($approveState))
        {
            IntInArrayValidator::validate($approveState, [
                EventApproveStateEnum::APPROVED,
                EventApproveStateEnum::WAITING_FOR_ADD,
                EventApproveStateEnum::WAITING_FOR_UPDATE,
                EventApproveStateEnum::WAITING_FOR_DELETE
            ]);
        }
        $this->approveState = $approveState;
    }

    /**
     * @param int|null $featuredLevel
     */
    public function setFeaturedLevel(int $featuredLevel = null): void
    {
        if (!is_null($featuredLevel))
        {
            IntInArrayValidator::validate($featuredLevel, [
                EventFeaturedLevelEnum::STANDARD_RECOMMENDATION,
                EventFeaturedLevelEnum::EDITORIAL_TIP
            ]);
        }
        $this->featuredLevel = $featuredLevel;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return \DateTime
     */
    public function getStartAt(): \DateTime
    {
        return $this->startAt;
    }

    /**
     * @return \DateTime
     */
    public function getEndAt(): \DateTime
    {
        return $this->endAt;
    }

    /**
     * @return string
     */
    public function getPlaceDesc(): string
    {
        return $this->placeDesc;
    }

    /**
     * @return string
     */
    public function getPlaceLat(): string
    {
        return $this->placeLat;
    }

    /**
     * @return string
     */
    public function getPlaceLon(): string
    {
        return $this->placeLon;
    }

    /**
     * @return array
     */
    public function getCategoryIdsArr(): array
    {
        return $this->categoryIdsArr;
    }

    /**
     * @return array
     */
    public function getImages(): array
    {
        return $this->images;
    }

    /**
     * @return string
     */
    public function getAttachmentUrl(): string
    {
        return $this->attachmentUrl;
    }

    /**
     * @return string
     */
    public function getFee(): string
    {
        return $this->fee;
    }

    /**
     * @return string
     */
    public function getWebUrl(): string
    {
        return $this->webUrl;
    }

    /**
     * @return string
     */
    public function getFacebookUrl(): string
    {
        return $this->facebookUrl;
    }

    /**
     * @return int
     */
    public function getConsumerFlags(): int
    {
        return $this->consumerFlags;
    }

    /**
     * @return bool
     */
    public function getIsVisible(): bool
    {
        return $this->isVisible;
    }

    /**
     * @return int
     */
    public function getApproveState(): int
    {
        return $this->approveState;
    }

    /**
     * @return int
     */
    public function getFeaturedLevel(): int
    {
        return $this->featuredLevel;
    }

}