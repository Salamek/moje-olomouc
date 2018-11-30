<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Hydrator;


interface IPlaceCategory extends IHydrator
{
    /**
     * @param \Salamek\MojeOlomouc\Model\IPlaceCategory $placeCategory
     * @return array
     */
    public function toPrimitiveArray(\Salamek\MojeOlomouc\Model\IPlaceCategory $placeCategory): array;

    /**
     * @param array $modelData
     * @return \Salamek\MojeOlomouc\Model\IPlaceCategory
     */
    public function fromPrimitiveArray(array $modelData);
}