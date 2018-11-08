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
     * @param int $consumerFlags
     */
    public function setConsumerFlags(int $consumerFlags): void;

    /**
     * @param boolean $isVisible
     */
    public function setIsVisible(bool $isVisible): void;

    /**
     * @return string
     */
    public function getTitle(): string;

    /**
     * @return int
     */
    public function getConsumerFlags(): int;

    /**
     * @return boolean
     */
    public function isIsVisible(): bool;
}