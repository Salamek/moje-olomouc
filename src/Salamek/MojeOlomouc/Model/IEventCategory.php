<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Model;

/**
 * Interface IEventCategory
 * @package Salamek\MojeOlomouc\Model
 */
interface IEventCategory
{
    /**
     * @param string $title
     */
    public function setTitle(string $title): void;

    /**
     * @param bool $isVisible
     */
    public function setIsVisible(bool $isVisible): void;

    /**
     * @return string
     */
    public function getTitle(): string;

    /**
     * @return bool
     */
    public function getIsVisible(): bool;
}