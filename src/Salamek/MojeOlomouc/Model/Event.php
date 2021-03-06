<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Model;

use Salamek\MojeOlomouc\Enum\DateTime;
use Salamek\MojeOlomouc\Enum\EventApproveStateEnum;
use Salamek\MojeOlomouc\Enum\EventFeaturedLevelEnum;
use Salamek\MojeOlomouc\Validator\GpsFloatValidator;
use Salamek\MojeOlomouc\Validator\GpsLatitudeFloatValidator;
use Salamek\MojeOlomouc\Validator\GpsLongitudeFloatValidator;
use Salamek\MojeOlomouc\Validator\IntInArrayValidator;
use Salamek\MojeOlomouc\Validator\MaxLengthValidator;
use Salamek\MojeOlomouc\Validator\ObjectArrayValidator;

/**
 * Class Event
 * @package Salamek\MojeOlomouc\Model
 */
class Event implements IEvent
{
    use TEntityIdentifier;
    
    /** @var string */
    private $title;

    /** @var string */
    private $description;

    /** @var \DateTimeInterface */
    private $startAt;

    /** @var \DateTimeInterface */
    private $endAt;

    /** @var string */
    private $placeDesc;

    /** @var float */
    private $placeLat;

    /** @var float */
    private $placeLon;

    /** @var IIdentifier[]|EventCategory[] */
    private $categories;

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

    /** @var bool|null */
    private $isVisible;

    /** @var int|null */
    private $approveState;

    /** @var int|null */
    private $featuredLevel;

    /**
     * Event constructor.
     * @param string $title
     * @param string $description
     * @param \DateTimeInterface $startAt
     * @param \DateTimeInterface $endAt
     * @param string $placeDesc
     * @param float $placeLat
     * @param float $placeLon
     * @param IIdentifier[]|EventCategory[] $categories
     * @param EntityImage[] $images
     * @param string|null $attachmentUrl
     * @param string|null $fee
     * @param string|null $webUrl
     * @param string|null $facebookUrl
     * @param int|null $consumerFlags
     * @param bool|null $isVisible
     * @param int|null $approveState
     * @param int|null $featuredLevel
     * @param int|null $id
     */
    public function __construct(
        string $title,
        string $description,
        \DateTimeInterface $startAt,
        \DateTimeInterface $endAt,
        string $placeDesc,
        float $placeLat,
        float $placeLon,
        array $categories,
        array $images = [],
        string $attachmentUrl = null,
        string $fee = null,
        string $webUrl = null,
        string $facebookUrl = null,
        int $consumerFlags = null,
        bool $isVisible = null,
        int $approveState = null,
        int $featuredLevel = null,
        int $id = null
    )
    {
        $this->setTitle($title);
        $this->setDescription($description);
        $this->setStartAt($startAt);
        $this->setEndAt($endAt);
        $this->setPlaceDesc($placeDesc);
        $this->setPlaceLat($placeLat);
        $this->setPlaceLon($placeLon);
        $this->setCategories($categories);
        $this->setImages($images);
        $this->setAttachmentUrl($attachmentUrl);
        $this->setFee($fee);
        $this->setWebUrl($webUrl);
        $this->setFacebookUrl($facebookUrl);
        $this->setConsumerFlags($consumerFlags);
        $this->setIsVisible($isVisible);
        $this->setApproveState($approveState);
        $this->setFeaturedLevel($featuredLevel);
        $this->setEntityIdentifier($id);
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
     * @param \DateTimeInterface $startAt
     */
    public function setStartAt(\DateTimeInterface $startAt): void
    {
        $this->startAt = $startAt;
    }

    /**
     * @param \DateTimeInterface $endAt
     */
    public function setEndAt(\DateTimeInterface $endAt): void
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
     * @param float $placeLat
     */
    public function setPlaceLat(float $placeLat): void
    {
        GpsLatitudeFloatValidator::validate($placeLat);
        GpsFloatValidator::validate($placeLat);
        $this->placeLat = $placeLat;
    }

    /**
     * @param float $placeLon
     */
    public function setPlaceLon(float $placeLon): void
    {
        GpsLongitudeFloatValidator::validate($placeLon);
        GpsFloatValidator::validate($placeLon);
        $this->placeLon = $placeLon;
    }

    /**
     * @param IIdentifier[]|EventCategory[]
     */
    public function setCategories(array $categories): void
    {
        ObjectArrayValidator::validate($categories, IIdentifier::class);
        $this->categories = $categories;
    }

    /**
     * @param EntityImage[] $images
     */
    public function setImages(array $images): void
    {
        ObjectArrayValidator::validate($images, EntityImage::class);
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
     * @param bool|null $isVisible
     */
    public function setIsVisible(bool $isVisible = null): void
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
     * @return \DateTimeInterface
     */
    public function getStartAt(): \DateTimeInterface
    {
        return $this->startAt;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getEndAt(): \DateTimeInterface
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
     * @return float
     */
    public function getPlaceLat(): float
    {
        return $this->placeLat;
    }

    /**
     * @return float
     */
    public function getPlaceLon(): float
    {
        return $this->placeLon;
    }

    /**
     * @return IIdentifier[]|EventCategory[]
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    /**
     * @return EntityImage[]
     */
    public function getImages(): array
    {
        return $this->images;
    }

    /**
     * @return string
     */
    public function getAttachmentUrl(): ?string
    {
        return $this->attachmentUrl;
    }

    /**
     * @return string
     */
    public function getFee(): ?string
    {
        return $this->fee;
    }

    /**
     * @return string
     */
    public function getWebUrl(): ?string
    {
        return $this->webUrl;
    }

    /**
     * @return string
     */
    public function getFacebookUrl(): ?string
    {
        return $this->facebookUrl;
    }

    /**
     * @return int
     */
    public function getConsumerFlags(): ?int
    {
        return $this->consumerFlags;
    }

    /**
     * @return bool|null
     */
    public function getIsVisible(): ?bool
    {
        return $this->isVisible;
    }

    /**
     * @return int
     */
    public function getApproveState(): ?int
    {
        return $this->approveState;
    }

    /**
     * @return int
     */
    public function getFeaturedLevel(): ?int
    {
        return $this->featuredLevel;
    }
}