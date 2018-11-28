<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Hydrator;

/**
 * Class EventCategory
 * @package Salamek\MojeOlomouc\Model
 */
class EventCategory implements IEventCategory
{
    /**
     * @var string
     */
    private $modelClass;

    /**
     * EventCategory constructor.
     * @param string $modelClass
     */
    public function __construct(string $modelClass)
    {
        $this->modelClass = $modelClass;
    }

    /**
     * @param \Salamek\MojeOlomouc\Model\IEventCategory $eventCategory
     * @return array
     */
    public function toPrimitiveArray(\Salamek\MojeOlomouc\Model\IEventCategory $eventCategory): array
    {
        $primitiveArray = [
            'title' => $eventCategory->getTitle()
        ];

        if (!is_null($eventCategory->getIsVisible())) $primitiveArray['isVisible'] = $eventCategory->getIsVisible();

        return $primitiveArray;
    }

    /**
     * @param array $modelData
     * @return \Salamek\MojeOlomouc\Model\IEventCategory
     */
    public function fromPrimitiveArray(array $modelData): \Salamek\MojeOlomouc\Model\IEventCategory
    {
        return new $this->modelClass(
            $modelData['title'],
            (array_key_exists('isVisible', $modelData) ? $modelData['isVisible']: null),
            (array_key_exists('id', $modelData) ? $modelData['id']: null)
        );
    }
}