<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Hydrator;


interface IImportantMessage extends IHydrator
{
    /**
     * @param \Salamek\MojeOlomouc\Model\IImportantMessage $importantMessage
     * @return array
     */
    public function toPrimitiveArray(\Salamek\MojeOlomouc\Model\IImportantMessage $importantMessage): array;

    /**
     * @param array $modelData
     * @return \Salamek\MojeOlomouc\Model\IImportantMessage
     */
    public function fromPrimitiveArray(array $modelData);
}