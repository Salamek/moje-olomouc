<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Hydrator;



/**
 * Class PlaceCategory
 * @package Salamek\MojeOlomouc\Model
 */
class PlaceCategory implements IPlaceCategory
{
    /**
     * @var string
     */
    private $modelClass;

    /**
     * PlaceCategory constructor.
     * @param string $modelClass
     */
    public function __construct(string $modelClass)
    {
        $this->modelClass = $modelClass;
    }

    /**
     * @param \Salamek\MojeOlomouc\Model\IPlaceCategory $placeCategory
     * @return array
     */
    public function toPrimitiveArray(\Salamek\MojeOlomouc\Model\IPlaceCategory $placeCategory): array
    {
        // Required
        $primitiveArray = [
            'title' => $placeCategory->getTitle(),
        ];

        // Optional
        if (!is_null($placeCategory->getConsumerFlags())) $primitiveArray['consumerFlags'] = $placeCategory->getConsumerFlags();
        if (!is_null($placeCategory->getIsVisible())) $primitiveArray['isVisible'] = $placeCategory->getIsVisible();

        return $primitiveArray;
    }

    /**
     * @param array $modelData
     * @return \Salamek\MojeOlomouc\Model\IPlaceCategory
     */
    public function fromPrimitiveArray(array $modelData): \Salamek\MojeOlomouc\Model\IPlaceCategory
    {
        return new $this->modelClass(
            $modelData['title'],
            (array_key_exists('consumerFlags', $modelData) ? $modelData['consumerFlags'] : null),
            (array_key_exists('isVisible', $modelData) ? $modelData['isVisible'] : null),
            (array_key_exists('id', $modelData) ? $modelData['id'] : null)
        );
    }
    
}