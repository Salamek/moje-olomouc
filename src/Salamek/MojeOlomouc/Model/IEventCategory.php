<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Model;

/**
 * Interface IEventCategory
 * @package Salamek\MojeOlomouc\Model
 */
interface IEventCategory extends IModel
{
    /**
     * @param string $title
     */
    public function setTitle(string $title): void;

    /**
     * @param bool|null $isVisible
     */
    public function setIsVisible(bool $isVisible = null): void;
    
    /**
     * @return string
     */
    public function getTitle(): string;

    /**
     * @return bool|null
     */
    public function getIsVisible(): ?bool;
}