<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Hydrator;


interface IPlace extends IHydrator
{
    /**
     * @param \Salamek\MojeOlomouc\Model\IPlace $place
     * @return array
     */
    public function toPrimitiveArray(\Salamek\MojeOlomouc\Model\IPlace $place): array;

    /**
     * @param array $modelData
     * @return \Salamek\MojeOlomouc\Model\IPlace
     */
    public function fromPrimitiveArray(array $modelData);
}