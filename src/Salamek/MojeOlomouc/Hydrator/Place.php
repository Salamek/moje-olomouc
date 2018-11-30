<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Hydrator;


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
    /**
     * @var string
     */
    private $modelClass;

    /**
     * @var IEntityImage
     */
    private $entityImageHydrator;

    /**
     * Place constructor.
     * @param string $modelClass
     * @param IEntityImage $entityImageHydrator
     */
    public function __construct(string $modelClass, IEntityImage $entityImageHydrator)
    {
        $this->modelClass = $modelClass;
        $this->entityImageHydrator = $entityImageHydrator;
    }

    /**
     * @param \Salamek\MojeOlomouc\Model\IPlace $place
     * @return array
     */
    public function toPrimitiveArray(\Salamek\MojeOlomouc\Model\IPlace $place): array
    {
        $primitiveImages = [];
        foreach ($place->getImages() AS $image)
        {
            $primitiveImages[] = $this->entityImageHydrator->toPrimitiveArray($image);
        }

        // Required
        $primitiveArray = [
            'title' => $place->getTitle(),
            'description' => $place->getDescription(),
            'address' => $place->getAddress(),
            'lat' => $place->getLat(),
            'lon' => $place->getLon(),
            'categoryId' => $place->getCategory()->getEntityIdentifier(),
            'images' => $primitiveImages,
        ];

        // Optional
        if (!is_null($place->getAttachmentUrl())) $primitiveArray['attachmentUrl'] = $place->getAttachmentUrl();
        if (!is_null($place->getIsVisible())) $primitiveArray['isVisible'] = $place->getIsVisible();
        if (!is_null($place->getApproveState())) $primitiveArray['approveState'] = $place->getApproveState();

        return $primitiveArray;
    }

    /**
     * @param array $modelData
     * @return \Salamek\MojeOlomouc\Model\IPlace
     */
    public function fromPrimitiveArray(array $modelData): \Salamek\MojeOlomouc\Model\IPlace
    {
        $images = [];
        if (array_key_exists('images', $modelData))
        {
            foreach($modelData['images'] AS $primitiveImage)
            {
                $images[] = $this->entityImageHydrator->fromPrimitiveArray($primitiveImage);
            }
        }

        return new $this->modelClass(
            $modelData['title'],
            $modelData['description'],
            $modelData['address'],
            $modelData['lat'],
            $modelData['lon'],
            new \Salamek\MojeOlomouc\Model\Identifier($modelData['categoryId']),
            $images,
            (array_key_exists('attachmentUrl', $modelData) ? $modelData['attachmentUrl'] : null),
            (array_key_exists('isVisible', $modelData) ? $modelData['isVisible'] : null),
            (array_key_exists('approveState', $modelData) ? $modelData['approveState'] : null),
            (array_key_exists('id', $modelData) ? $modelData['id'] : null)
        );
    }
}