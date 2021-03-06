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
     * @param int|null $entityIdentifier
     */
    public function setEntityIdentifier(int $entityIdentifier = null): void;

    /**
     * @return int
     */
    public function getEntityIdentifier(): ?int;
}