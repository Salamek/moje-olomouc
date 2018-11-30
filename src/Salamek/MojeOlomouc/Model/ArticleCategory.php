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
    use TEntityIdentifier;

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
     * @param int|null $consumerFlags
     * @param bool|null $isImportant
     * @param bool|null $isVisible
     * @param int|null $id
     */
    public function __construct(string $title, int $consumerFlags = null, bool $isImportant = null, bool $isVisible = null, int $id = null)
    {
        $this->setTitle($title);
        $this->setConsumerFlags($consumerFlags);
        $this->setIsImportant($isImportant);
        $this->setIsVisible($isVisible);
        $this->setEntityIdentifier($id);
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
     * @param int|null $consumerFlags
     */
    public function setConsumerFlags(int $consumerFlags = null): void
    {
        $this->consumerFlags = $consumerFlags;
    }

    /**
     * @param boolean $isImportant
     */
    public function setIsImportant(bool $isImportant = null): void
    {
        $this->isImportant = $isImportant;
    }

    /**
     * @param boolean $isVisible
     */
    public function setIsVisible(bool $isVisible = null): void
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
     * @return int|null
     */
    public function getConsumerFlags(): ?int
    {
        return $this->consumerFlags;
    }

    /**
     * @return boolean|null
     */
    public function getIsImportant(): ?bool
    {
        return $this->isImportant;
    }

    /**
     * @return boolean|null
     */
    public function getIsVisible(): ?bool
    {
        return $this->isVisible;
    }
}