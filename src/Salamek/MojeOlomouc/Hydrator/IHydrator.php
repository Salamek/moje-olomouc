<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Hydrator;

/**
 * Interface IHydrator
 * @package Salamek\MojeOlomouc\Hydrator
 */
interface IHydrator
{
    /**
     * @param $model
     * @return array
     */
    //public function toPrimitiveArray(IModel $model): array;

    /**
     * @param array $modelData
     * @return mixed
     */
    public function fromPrimitiveArray(array $modelData);
}