<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Model;


use Salamek\MojeOlomouc\Validator\MaxLengthValidator;

/**
 * Class PlaceCategory
 * @package Salamek\MojeOlomouc\Model
 */
class PlaceCategory implements IPlaceCategory
{
    use TIdentifier;

    /** @var string */
    private $title;

    /** @var int */
    private $consumerFlags;

    /** @var bool */
    private $isVisible;

    /**
     * PlaceCategory constructor.
     * @param string $title
     * @param int|null $consumerFlags
     * @param bool $isVisible
     * @param int|null $id
     */
    public function __construct(string $title, int $consumerFlags = null, $isVisible = true, int $id = null)
    {
        $this->setTitle($title);
        $this->setConsumerFlags($consumerFlags);
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
            'isVisible' => $this->isVisible
        ];
    }
    
}