<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Model;

/**
 * Interface IModel
 * @package Salamek\MojeOlomouc\Model
 */
interface IModel
{
    /**
     * @param int|null $id
     */
    public function setId(int $id = null): void;

    /**
     * @return int
     */
    public function getId(): ?int;

    /**
     * @return array
     */
    public function toPrimitiveArray(): array;

    /**
     * @param array $modelData
     * @return IModel
     */
    public static function fromPrimitiveArray(array $modelData);
}