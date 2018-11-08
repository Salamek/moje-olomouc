<?php
/**
 * Created by PhpStorm.
 * User: sadam
 * Date: 11/8/18
 * Time: 2:39 PM
 */

namespace Salamek\MojeOlomouc\Model;

/**
 * Interface IModel
 * @package Salamek\MojeOlomouc\Model
 */
interface IModel
{
    /**
     * @param int $id
     */
    public function setId(int $id): void;

    /**
     * @return int
     */
    public function getId(): int;

    /**
     * @return array
     */
    public function toPrimitiveArray(): array;
}