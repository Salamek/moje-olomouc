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
     * @param int|null $id
     */
    public function setId(int $id = null): void
    {
        $this->id = $id;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }
}