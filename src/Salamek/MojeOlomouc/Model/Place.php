<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Model;


use Salamek\MojeOlomouc\Enum\PlaceApproveStateEnum;
use Salamek\MojeOlomouc\Validator\GpsStringValidator;
use Salamek\MojeOlomouc\Validator\IntInArrayValidator;
use Salamek\MojeOlomouc\Validator\MaxLengthValidator;

/**
 * Class Place
 * @package Salamek\MojeOlomouc\Model
 */
class Place implements IPlace
{
    use TIdentifier;
    
    /** @var string */
    private $title;

    /** @var string */
    private $description;

    /** @var string */
    private $address;

    /** @var string */
    private $lat;

    /** @var string */
    private $lon;

    /** @var int */
    private $categoryId;

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
     * @param string $lat
     * @param string $lon
     * @param int $categoryId
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
        string $lat,
        string $lon,
        int $categoryId,
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
        $this->setCategoryId($categoryId);
        $this->setImages($images);
        $this->setAttachmentUrl($attachmentUrl);
        $this->setIsVisible($isVisible);
        $this->setApproveState($approveState);
        $this->setId($id);
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
     * @param string $lat
     */
    public function setLat(string $lat): void
    {
        GpsStringValidator::validate($lat);
        $this->lat = $lat;
    }

    /**
     * @param string $lon
     */
    public function setLon(string $lon): void
    {
        GpsStringValidator::validate($lon);
        $this->lon = $lon;
    }

    /**
     * @param int $categoryId
     */
    public function setCategoryId(int $categoryId): void
    {
        $this->categoryId = $categoryId;
    }

    /**
     * @param EntityImage[] $images
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
     * @return string
     */
    public function getLat(): string
    {
        return $this->lat;
    }

    /**
     * @return string
     */
    public function getLon(): string
    {
        return $this->lon;
    }

    /**
     * @return int
     */
    public function getCategoryId(): int
    {
        return $this->categoryId;
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

    /**
     * @return array
     */
    public function toPrimitiveArray(): array
    {
        $primitiveImages = [];
        foreach ($this->images AS $image)
        {
            $primitiveImages[] = $image->toPrimitiveArray();
        }

        // Required
        $primitiveArray = [
            'title' => $this->title,
            'description' => $this->description,
            'address' => $this->address,
            'lat' => $this->lat,
            'lon' => $this->lon,
            'categoryId' => $this->categoryId,
            'images' => $primitiveImages,
        ];

        // Optional
        if (!is_null($this->attachmentUrl)) $primitiveArray['attachmentUrl'] = $this->attachmentUrl;
        if (!is_null($this->isVisible)) $primitiveArray['isVisible'] = $this->isVisible;
        if (!is_null($this->approveState)) $primitiveArray['approveState'] = $this->approveState;

        return $primitiveArray;
    }

    /**
     * @param array $modelData
     * @return Place
     */
    public static function fromPrimitiveArray(array $modelData): Place
    {
        $images = [];
        if (array_key_exists('images', $modelData))
        {
            foreach($modelData['images'] AS $primitiveImage)
            {
                $images[] = EntityImage::fromPrimitiveArray($primitiveImage);
            }
        }

        return new Place(
            $modelData['title'],
            $modelData['description'],
            $modelData['address'],
            $modelData['lat'],
            $modelData['lon'],
            $modelData['categoryId'],
            $images,
            (array_key_exists('attachmentUrl', $modelData) ? $modelData['attachmentUrl'] : null),
            (array_key_exists('isVisible', $modelData) ? $modelData['isVisible'] : null),
            (array_key_exists('approveState', $modelData) ? $modelData['approveState'] : null),
            (array_key_exists('id', $modelData) ? $modelData['id'] : null)
        );
    }
}