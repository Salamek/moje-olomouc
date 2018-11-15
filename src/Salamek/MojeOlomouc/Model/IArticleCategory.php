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
     * @param boolean|null $isImportant
     */
    public function setIsImportant(bool $isImportant = null): void;

    /**
     * @param boolean|null $isVisible
     */
    public function setIsVisible(bool $isVisible = null): void;
    
    /**
     * @return string
     */
    public function getTitle(): string;

    /**
     * @return int
     */
    public function getConsumerFlags(): ?int;

    /**
     * @return boolean|null
     */
    public function getIsImportant(): ?bool;

    /**
     * @return boolean|null
     */
    public function getIsVisible(): ?bool;
}