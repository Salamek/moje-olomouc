<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Hydrator;


interface IArticle extends IHydrator
{
    /**
     * @param \Salamek\MojeOlomouc\Model\IArticle $article
     * @return array
     */
    public function toPrimitiveArray(\Salamek\MojeOlomouc\Model\IArticle $article): array;

    /**
     * @param array $modelData
     * @return \Salamek\MojeOlomouc\Model\IArticle
     */
    public function fromPrimitiveArray(array $modelData);
}