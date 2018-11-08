<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Model;

/**
 * Class EventCategory
 * @package Salamek\MojeOlomouc\Model
 */
class EventCategory implements IEventCategory
{
    /** @var string */
    private $title;

    /** @var bool */
    private $isVisible;

    /**
     * EventCategory constructor.
     * @param string $title
     * @param bool $isVisible
     */
    public function __construct(string $title, bool $isVisible = true)
    {
        $this->setTitle($title);
        $this->setIsVisible($isVisible);
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @param bool $isVisible
     */
    public function setIsVisible(bool $isVisible = true): void
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
     * @return bool
     */
    public function getIsVisible(): bool
    {
        return $this->isVisible;
    }


}