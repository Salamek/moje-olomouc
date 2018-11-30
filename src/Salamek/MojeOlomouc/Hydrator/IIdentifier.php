<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Hydrator;


interface IIdentifier extends IHydrator
{
    /**
     * @param \Salamek\MojeOlomouc\Model\IModel $model
     * @return array
     */
    public function toPrimitiveArray(\Salamek\MojeOlomouc\Model\IModel $model): array;

    /**
     * @param array $modelData
     * @return \Salamek\MojeOlomouc\Model\IModel
     */
    public function fromPrimitiveArray(array $modelData);
}