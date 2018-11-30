<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Hydrator;


interface IEventCategory extends IHydrator
{
    /**
     * @param \Salamek\MojeOlomouc\Model\IEventCategory $eventCategory
     * @return array
     */
    public function toPrimitiveArray(\Salamek\MojeOlomouc\Model\IEventCategory $eventCategory): array;

    /**
     * @param array $modelData
     * @return \Salamek\MojeOlomouc\Model\IEventCategory
     */
    public function fromPrimitiveArray(array $modelData);
}