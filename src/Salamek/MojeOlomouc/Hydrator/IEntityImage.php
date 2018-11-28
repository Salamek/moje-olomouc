<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Hydrator;


interface IEntityImage extends IHydrator
{
    /**
     * @param \Salamek\MojeOlomouc\Model\IEntityImage $entityImage
     * @return array
     */
    public function toPrimitiveArray(\Salamek\MojeOlomouc\Model\IEntityImage $entityImage): array;

    /**
     * @param array $modelData
     * @return \Salamek\MojeOlomouc\Model\IEntityImage
     */
    public function fromPrimitiveArray(array $modelData);
}