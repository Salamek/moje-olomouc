<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Hydrator;

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
    /**
     * @var string
     */
    private $modelClass;

    /**
     * @var IEntityImage
     */
    private $entityImageHydrator;

    /**
     * Event constructor.
     * @param string $modelClass
     * @param IEntityImage $entityImageHydrator
     */
    public function __construct(string $modelClass, IEntityImage $entityImageHydrator)
    {
        $this->modelClass = $modelClass;
        $this->entityImageHydrator = $entityImageHydrator;
    }


    /**
     * @param \Salamek\MojeOlomouc\Model\IEvent $event
     * @return array
     */
    public function toPrimitiveArray(\Salamek\MojeOlomouc\Model\IEvent $event): array
    {
        $primitiveImages = [];
        foreach ($event->getImages() AS $image)
        {
            $primitiveImages[] = $this->entityImageHydrator->toPrimitiveArray($image);
        }

        // Required
        $primitiveArray = [
            'title' => $event->getTitle(),
            'description' => $event->getDescription(),
            'startAt' => ($event->getStartAt() ? $event->getStartAt()->format(DateTime::NOT_A_ISO8601): null),
            'endAt' => ($event->getEndAt() ? $event->getEndAt()->format(DateTime::NOT_A_ISO8601): null),
            'placeDesc' => $event->getPlaceDesc(),
            'placeLat' => $event->getPlaceLat(),
            'placeLon' => $event->getPlaceLon(),
            'categoryIdsArr' => $event->getCategoryIdsArr(),
            'images'   => $primitiveImages
        ];

        // Optional
        if (!is_null($event->getAttachmentUrl())) $primitiveArray['attachmentUrl'] = $event->getAttachmentUrl();
        if (!is_null($event->getFee())) $primitiveArray['fee'] = $event->getFee();
        if (!is_null($event->getWebUrl())) $primitiveArray['webUrl'] = $event->getWebUrl();
        if (!is_null($event->getFacebookUrl())) $primitiveArray['facebookUrl'] = $event->getFacebookUrl();
        if (!is_null($event->getConsumerFlags())) $primitiveArray['consumerFlags'] = $event->getConsumerFlags();
        if (!is_null($event->getIsVisible())) $primitiveArray['isVisible'] = $event->getIsVisible();
        if (!is_null($event->getApproveState())) $primitiveArray['approveState'] = $event->getApproveState();
        if (!is_null($event->getFeaturedLevel())) $primitiveArray['featuredLevel'] = $event->getFeaturedLevel();

        return $primitiveArray;
    }

    /**
     * @param array $modelData
     * @return \Salamek\MojeOlomouc\Model\IEvent
     */
    public function fromPrimitiveArray(array $modelData): \Salamek\MojeOlomouc\Model\IEvent
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
            \DateTime::createFromFormat(DateTime::NOT_A_ISO8601, $modelData['startAt']),
            \DateTime::createFromFormat(DateTime::NOT_A_ISO8601, $modelData['endAt']),
            $modelData['placeDesc'],
            floatval($modelData['placeLat']), //@TODO API returns string, but accepts float, we assume that float is a correct variable type
            floatval($modelData['placeLon']), //@TODO API returns string, but accepts float, we assume that float is a correct variable type
            $modelData['categoryIdsArr'],
            $images,
            (array_key_exists('attachmentUrl', $modelData) ? $modelData['attachmentUrl'] : null),
            (array_key_exists('fee', $modelData) ? $modelData['fee'] : null),
            (array_key_exists('webUrl', $modelData) ? $modelData['webUrl'] : null),
            (array_key_exists('facebookUrl', $modelData) ? $modelData['facebookUrl'] : null),
            (array_key_exists('consumerFlags', $modelData) ? $modelData['consumerFlags'] : null),
            (array_key_exists('isVisible', $modelData) ? $modelData['isVisible'] : null),
            (array_key_exists('approveState', $modelData) ? $modelData['approveState'] : null),
            (array_key_exists('featuredLevel', $modelData) ? $modelData['featuredLevel'] : null),
            (array_key_exists('id', $modelData) ? $modelData['id'] : null)
        );
    }
}