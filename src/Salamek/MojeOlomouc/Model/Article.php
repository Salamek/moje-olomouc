<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Model;
use Salamek\MojeOlomouc\Enum\ArticleApproveStateEnum;
use Salamek\MojeOlomouc\Enum\DateTime;
use Salamek\MojeOlomouc\Validator\IntInArrayValidator;
use Salamek\MojeOlomouc\Validator\MaxLengthValidator;
use Salamek\MojeOlomouc\Validator\ObjectArrayValidator;

/**
 * Class Article
 * @package Salamek\MojeOlomouc\Model
 */
class Article implements IArticle
{
    use TEntityIdentifier;
    
    /** @var string */
    private $title;

    /** @var string */
    private $content;

    /** @var string */
    private $author;

    /** @var IIdentifier|ArticleCategory */
    private $category;

    /** @var \DateTimeInterface */
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
     * @param IIdentifier|ArticleCategory $category
     * @param \DateTimeInterface $dateTimeAt
     * @param EntityImage[] $images
     * @param string|null $attachmentUrl
     * @param bool|null $isVisible
     * @param bool|null $isImportant
     * @param int|null $approveState
     * @param int|null $id
     */
    public function __construct(
        string $title,
        string $content,
        string $author,
        IIdentifier $category,
        \DateTimeInterface $dateTimeAt,
        array $images = [],
        string $attachmentUrl = null,
        bool $isVisible = null,
        bool $isImportant = null,
        int $approveState = null,
        int $id = null
    )
    {
        $this->setTitle($title);
        $this->setContent($content);
        $this->setAuthor($author);
        $this->setCategory($category);
        $this->setDateTimeAt($dateTimeAt);
        $this->setImages($images);
        $this->setAttachmentUrl($attachmentUrl);
        $this->setIsVisible($isVisible);
        $this->setIsImportant($isImportant);
        $this->setApproveState($approveState);
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
     * @param IIdentifier|ArticleCategory $category
     */
    public function setCategory(IIdentifier $category): void
    {
        $this->category = $category;
    }

    /**
     * @param \DateTimeInterface $dateTimeAt
     */
    public function setDateTimeAt(\DateTimeInterface $dateTimeAt): void
    {
        $this->dateTimeAt = $dateTimeAt;
    }

    /**
     * @param EntityImage[] $images
     */
    public function setImages(array $images): void
    {
        ObjectArrayValidator::validate($images, EntityImage::class);
        $this->images = $images;
    }

    /**
     * @param string $attachmentUrl
     */
    public function setAttachmentUrl(string $attachmentUrl = null): void
    {
        $this->attachmentUrl = $attachmentUrl;
    }

    /**
     * @param boolean|null $isVisible
     */
    public function setIsVisible(bool $isVisible = null): void
    {
        $this->isVisible = $isVisible;
    }

    /**
     * @param boolean|null $isImportant
     */
    public function setIsImportant(bool $isImportant = null): void
    {
        $this->isImportant = $isImportant;
    }

    /**
     * @param int $approveState
     */
    public function setApproveState(int $approveState = null): void
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
     * @return IIdentifier
     */
    public function getCategory(): IIdentifier
    {
        return $this->category;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getDateTimeAt(): \DateTimeInterface
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
     * @return string|null
     */
    public function getAttachmentUrl(): ?string
    {
        return $this->attachmentUrl;
    }

    /**
     * @return boolean|null
     */
    public function getIsVisible(): ?bool
    {
        return $this->isVisible;
    }

    /**
     * @return boolean|null
     */
    public function getIsImportant(): ?bool
    {
        return $this->isImportant;
    }

    /**
     * @return int|null
     */
    public function getApproveState(): ?int
    {
        return $this->approveState;
    }
}