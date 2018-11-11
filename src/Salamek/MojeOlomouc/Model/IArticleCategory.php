<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Model;

/**
 * Interface IArticleCategory
 * @package Salamek\MojeOlomouc\Model
 */
interface IArticleCategory extends IModel
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
     * @param boolean $isImportant
     */
    public function setIsImportant(bool $isImportant): void;

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
    public function getConsumerFlags(): ?int;

    /**
     * @return boolean
     */
    public function getIsImportant(): bool;

    /**
     * @return boolean
     */
    public function getIsVisible(): bool;
}