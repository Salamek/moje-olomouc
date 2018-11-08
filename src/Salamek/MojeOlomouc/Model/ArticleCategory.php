<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Model;


use Salamek\MojeOlomouc\Validator\MaxLengthValidator;

/**
 * Class ArticleCategory
 * @package Salamek\MojeOlomouc\Model
 */
class ArticleCategory implements IArticleCategory
{
    /** @var string */
    private $title;

    /** @var int */
    private $consumerFlags;

    /** @var bool */
    private $isImportant;

    /** @var bool */
    private $isVisible;

    /**
     * ArticleCategory constructor.
     * @param string $title
     * @param int $consumerFlags
     * @param bool $isImportant
     * @param bool $isVisible
     */
    public function __construct(string $title, int $consumerFlags = null, bool $isImportant = false, bool $isVisible = true)
    {
        $this->setTitle($title);
        $this->setConsumerFlags($consumerFlags);
        $this->setIsImportant($isImportant);
        $this->setIsVisible($isVisible);
    }
    
    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        MaxLengthValidator::validate($title, 150);
        $this->title = $title;
    }

    /**
     * @param int $consumerFlags
     */
    public function setConsumerFlags(int $consumerFlags): void
    {
        $this->consumerFlags = $consumerFlags;
    }

    /**
     * @param boolean $isImportant
     */
    public function setIsImportant(bool $isImportant): void
    {
        $this->isImportant = $isImportant;
    }

    /**
     * @param boolean $isVisible
     */
    public function setIsVisible(bool $isVisible): void
    {
        $this->isVisible = $isVisible;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return int
     */
    public function getConsumerFlags(): int
    {
        return $this->consumerFlags;
    }

    /**
     * @return boolean
     */
    public function isIsImportant(): bool
    {
        return $this->isImportant;
    }

    /**
     * @return boolean
     */
    public function isIsVisible(): bool
    {
        return $this->isVisible;
    }

}