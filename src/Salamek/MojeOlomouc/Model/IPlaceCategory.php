<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Model;

/**
 * Interface IPlaceCategory
 * @package Salamek\MojeOlomouc\Model
 */
interface IPlaceCategory extends IModel
{
    /**
     * @param string $title
     */
    public function setTitle(string $title): void;

    /**
     * @param int|null $consumerFlags
     */
    public function setConsumerFlags(int $consumerFlags = null): void;

    /**
     * @param boolean $isVisible
     */
    public function setIsVisible(bool $isVisible): void;

    /**
     * @return string
     */
    public function getTitle(): string;

    /**
     * @return int|null
     */
    public function getConsumerFlags(): ?int;

    /**
     * @return boolean
     */
    public function getIsVisible(): bool;
}