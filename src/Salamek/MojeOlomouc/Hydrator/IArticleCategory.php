<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Hydrator;



interface IArticleCategory extends IHydrator
{
    /**
     * @param \Salamek\MojeOlomouc\Model\IArticleCategory $articleCategory
     * @return array
     */
    public function toPrimitiveArray(\Salamek\MojeOlomouc\Model\IArticleCategory $articleCategory): array;

    /**
     * @param array $modelData
     * @return \Salamek\MojeOlomouc\Model\IArticleCategory
     */
    public function fromPrimitiveArray(array $modelData);
}