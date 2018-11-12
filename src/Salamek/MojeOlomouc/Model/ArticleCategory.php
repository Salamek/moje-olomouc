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
    use TIdentifier;

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
     * @param bool $isImportant
     * @param bool $isVisible
     * @param int|null $id
     */
    public function __construct(string $title, int $consumerFlags = null, bool $isImportant = false, bool $isVisible = true, int $id = null)
    {
        $this->setTitle($title);
        $this->setConsumerFlags($consumerFlags);
        $this->setIsImportant($isImportant);
        $this->setIsVisible($isVisible);
        $this->setId($id);
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
     * @return int|null
     */
    public function getConsumerFlags(): ?int
    {
        return $this->consumerFlags;
    }

    /**
     * @return boolean
     */
    public function getIsImportant(): bool
    {
        return $this->isImportant;
    }

    /**
     * @return boolean
     */
    public function getIsVisible(): bool
    {
        return $this->isVisible;
    }

    /**
     * @return array
     */
    public function toPrimitiveArray(): array
    {
        return [
            'title' => $this->title,
            'consumerFlags' => $this->consumerFlags,
            'isImportant' => $this->isImportant,
            'isVisible' => $this->isVisible
        ];
    }

    /**
     * @param array $modelData
     * @return ArticleCategory
     */
    public static function fromPrimitiveArray(array $modelData): ArticleCategory
    {
        return new ArticleCategory(
            $modelData['title'],
            (array_key_exists('consumerFlags', $modelData) ? $modelData['consumerFlags']: null),
            (array_key_exists('isImportant', $modelData) ? $modelData['isImportant']: false),
            (array_key_exists('isVisible', $modelData) ? $modelData['isVisible']: true),
            (array_key_exists('id', $modelData) ? $modelData['id']: null)
        );
    }
}