<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Model;

/**
 * Class EventCategory
 * @package Salamek\MojeOlomouc\Model
 */
class EventCategory implements IEventCategory
{
    use TIdentifier;
    
    /** @var string */
    private $title;

    /** @var bool */
    private $isVisible;

    /**
     * EventCategory constructor.
     * @param string $title
     * @param bool $isVisible
     * @param null $id
     */
    public function __construct(string $title, bool $isVisible = true, $id = null)
    {
        $this->setTitle($title);
        $this->setIsVisible($isVisible);
        $this->setId($id);
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

    /**
     * @return array
     */
    public function toPrimitiveArray(): array
    {
        return [
            'title' => $this->title,
            'isVisible' => $this->isVisible
        ];
    }


}