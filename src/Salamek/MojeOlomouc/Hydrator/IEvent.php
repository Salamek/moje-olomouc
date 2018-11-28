<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Hydrator;


interface IEvent extends IHydrator
{
    /**
     * @param \Salamek\MojeOlomouc\Model\IEvent $event
     * @return array
     */
    public function toPrimitiveArray(\Salamek\MojeOlomouc\Model\IEvent $event): array;

    /**
     * @param array $modelData
     * @return \Salamek\MojeOlomouc\Model\IEvent
     */
    public function fromPrimitiveArray(array $modelData);
}