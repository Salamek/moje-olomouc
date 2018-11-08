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
    /** @var string */
    private $title;

    /** @var int */
    private $consumerFlags;

    /** @var bool */
    private $isVisible;

    /**
     * PlaceCategory constructor.
     * @param string $title
     * @param int $consumerFlags
     * @param bool $isVisible
     */
    public function __construct(string $title, int $consumerFlags = null, $isVisible = true)
    {
        $this->setTitle($title);
        $this->setConsumerFlags($consumerFlags);
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
    public function isIsVisible(): bool
    {
        return $this->isVisible;
    }
}