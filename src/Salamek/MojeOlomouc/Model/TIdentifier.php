<?php
/**
 * Created by PhpStorm.
 * User: sadam
 * Date: 11/8/18
 * Time: 3:24 PM
 */

namespace Salamek\MojeOlomouc\Model;


trait TIdentifier
{
    /** @var int|null */
    public $id = null;

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}