<?php
/**
 * Created by PhpStorm.
 * User: sadam
 * Date: 11/8/18
 * Time: 3:24 PM
 */

namespace Salamek\MojeOlomouc\Model;


trait TEntityIdentifier
{
    /** @var int|null */
    private $entityIdentifier = null;

    /**
     * @param int|null $entityIdentifier
     */
    public function setEntityIdentifier(int $entityIdentifier = null): void
    {
        $this->entityIdentifier = $entityIdentifier;
    }

    /**
     * @return int|null
     */
    public function getEntityIdentifier(): ?int
    {
        return $this->entityIdentifier;
    }
}