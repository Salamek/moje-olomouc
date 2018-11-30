<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Model;


use Salamek\MojeOlomouc\Enum\PlaceApproveStateEnum;
use Salamek\MojeOlomouc\Validator\GpsFloatValidator;
use Salamek\MojeOlomouc\Validator\GpsLatitudeFloatValidator;
use Salamek\MojeOlomouc\Validator\GpsLongitudeFloatValidator;
use Salamek\MojeOlomouc\Validator\IntInArrayValidator;
use Salamek\MojeOlomouc\Validator\MaxLengthValidator;
use Salamek\MojeOlomouc\Validator\ObjectArrayValidator;

/**
 * Class Place
 * @package Salamek\MojeOlomouc\Model
 */
class Place implements IPlace
{
    use TEntityIdentifier;
    
    /** @var string */
    private $title;

    /** @var string */
    private $description;

    /** @var string */
    private $address;

    /** @var float */
    private $lat;

    /** @var float */
    private $lon;

    /** @var IIdentifier|PlaceCategory */
    private $category;

    /** @var EntityImage[] */
    private $images;

    /** @var string */
    private $attachmentUrl;

    /** @var bool */
    private $isVisible;

    /** @var int */
    private $approveState;

    /**
     * Place constructor.
     * @param string $title
     * @param string $description
     * @param string $address
     * @param float $lat
     * @param float $lon
     * @param IIdentifier|PlaceCategory $category
     * @param EntityImage[] $images
     * @param string|null $attachmentUrl
     * @param bool $isVisible
     * @param int|null $approveState
     * @param int|null $id
     */
    public function __construct(
        string $title,
        string $description,
        string $address,
        float $lat,
        float $lon,
        IIdentifier $category,
        array $images = [],
        string $attachmentUrl = null,
        bool $isVisible = null,
        int $approveState = null,
        int $id = null
    )
    {
        $this->setTitle($title);
        $this->setDescription($description);
        $this->setAddress($address);
        $this->setLat($lat);
        $this->setLon($lon);
        $this->setCategory($category);
        $this->setImages($images);
        $this->setAttachmentUrl($attachmentUrl);
        $this->setIsVisible($isVisible);
        $this->setApproveState($approveState);
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
        MaxLengthValidator::validate($description, 4000);
        $this->description = $description;
    }

    /**
     * @param string $address
     */
    public function setAddress(string $address): void
    {
        MaxLengthValidator::validate($address, 100);
        $this->address = $address;
    }

    /**
     * @param float $lat
     */
    public function setLat(float $lat): void
    {
        GpsLatitudeFloatValidator::validate($lat);
        GpsFloatValidator::validate($lat);
        $this->lat = $lat;
    }

    /**
     * @param float $lon
     */
    public function setLon(float $lon): void
    {
        GpsLongitudeFloatValidator::validate($lon);
        GpsFloatValidator::validate($lon);
        $this->lon = $lon;
    }

    /**
     * @param IIdentifier|PlaceCategory $category
     */
    public function setCategory(IIdentifier $category): void
    {
        $this->category = $category;
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
        $this->attachmentUrl = $attachmentUrl;
    }

    /**
     * @param boolean|null $isVisible
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
                PlaceApproveStateEnum::APPROVED,
                PlaceApproveStateEnum::WAITING_FOR_ADD,
                PlaceApproveStateEnum::WAITING_FOR_UPDATE,
                PlaceApproveStateEnum::WAITING_FOR_DELETE
            ]);
        }
        $this->approveState = $approveState;
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
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @return float
     */
    public function getLat(): float
    {
        return $this->lat;
    }

    /**
     * @return float
     */
    public function getLon(): float
    {
        return $this->lon;
    }

    /**
     * @return IIdentifier
     */
    public function getCategory(): IIdentifier
    {
        return $this->category;
    }

    /**
     * @return EntityImage[]
     */
    public function getImages(): array
    {
        return $this->images;
    }

    /**
     * @return string|null
     */
    public function getAttachmentUrl(): ?string
    {
        return $this->attachmentUrl;
    }

    /**
     * @return boolean|null
     */
    public function getIsVisible(): ?bool
    {
        return $this->isVisible;
    }

    /**
     * @return int|null
     */
    public function getApproveState(): ?int
    {
        return $this->approveState;
    }
}