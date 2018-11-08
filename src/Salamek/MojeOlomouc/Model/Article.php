<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Model;
use Salamek\MojeOlomouc\Exception\ArticleApproveStateEnum;
use Salamek\MojeOlomouc\Validator\IntInArrayValidator;
use Salamek\MojeOlomouc\Validator\MaxLengthValidator;

/**
 * Class Article
 * @package Salamek\MojeOlomouc\Model
 */
class Article implements IArticle
{
    /** @var string */
    private $title;

    /** @var string */
    private $content;

    /** @var string */
    private $author;

    /** @var int */
    private $categoryId;

    /** @var \DateTime */
    private $dateTimeAt;

    /** @var EntityImage[] */
    private $images;

    /** @var string */
    private $attachmentUrl;

    /** @var bool */
    private $isVisible;

    /** @var bool */
    private $isImportant;

    /** @var int */
    private $approveState;

    /**
     * Article constructor.
     * @param string $title
     * @param string $content
     * @param string $author
     * @param int $categoryId
     * @param \DateTime $dateTimeAt
     * @param EntityImage[] $images
     * @param string $attachmentUrl
     * @param bool $isVisible
     * @param bool $isImportant
     * @param int $approveState
     */
    public function __construct(
        string $title,
        string $content,
        string $author,
        int $categoryId,
        \DateTime $dateTimeAt,
        array $images = [],
        string $attachmentUrl = null,
        bool $isVisible = true,
        bool $isImportant = false,
        int $approveState = null
    )
    {
        $this->setTitle($title);
        $this->setContent($content);
        $this->setAuthor($author);
        $this->setCategoryId($categoryId);
        $this->setDateTimeAt($dateTimeAt);
        $this->setImages($images);
        $this->setAttachmentUrl($attachmentUrl);
        $this->setIsVisible($isVisible);
        $this->setIsImportant($isImportant);
        $this->setApproveState($approveState);
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
     * @param string $content
     */
    public function setContent(string $content): void
    {
        MaxLengthValidator::validate($content, 65535); // Not defined in docs. i assume TEXT col type
        $this->content = $content;
    }

    /**
     * @param string $author
     */
    public function setAuthor(string $author): void
    {
        MaxLengthValidator::validate($author, 150);
        $this->author = $author;
    }

    /**
     * @param int $categoryId
     */
    public function setCategoryId(int $categoryId): void
    {
        $this->categoryId = $categoryId;
    }

    /**
     * @param \DateTime $dateTimeAt
     */
    public function setDateTimeAt(\DateTime $dateTimeAt): void
    {
        $this->dateTimeAt = $dateTimeAt;
    }

    /**
     * @param EntityImage[] $images
     */
    public function setImages(array $images): void
    {
        $this->images = $images;
    }

    /**
     * @param string $attachmentUrl
     */
    public function setAttachmentUrl(string $attachmentUrl): void
    {
        $this->attachmentUrl = $attachmentUrl;
    }

    /**
     * @param boolean $isVisible
     */
    public function setIsVisible(bool $isVisible): void
    {
        $this->isVisible = $isVisible;
    }

    /**
     * @param boolean $isImportant
     */
    public function setIsImportant(bool $isImportant): void
    {
        $this->isImportant = $isImportant;
    }

    /**
     * @param int $approveState
     */
    public function setApproveState(int $approveState): void
    {
        if (!is_null($approveState))
        {
            IntInArrayValidator::validate($approveState, [
                ArticleApproveStateEnum::APPROVED,
                ArticleApproveStateEnum::WAITING_FOR_ADD,
                ArticleApproveStateEnum::WAITING_FOR_UPDATE,
                ArticleApproveStateEnum::WAITING_FOR_DELETE
            ]);
        }
        $this->approveState = $approveState;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * @return int
     */
    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    /**
     * @return \DateTime
     */
    public function getDateTimeAt(): \DateTime
    {
        return $this->dateTimeAt;
    }

    /**
     * @return EntityImage[]
     */
    public function getImages(): array
    {
        return $this->images;
    }

    /**
     * @return string
     */
    public function getAttachmentUrl(): string
    {
        return $this->attachmentUrl;
    }

    /**
     * @return boolean
     */
    public function getIsVisible(): bool
    {
        return $this->isVisible;
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
    public function getApproveState(): bool
    {
        return $this->approveState;
    }

}