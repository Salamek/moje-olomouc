<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Model;
use Salamek\MojeOlomouc\Validator\MaxLengthValidator;

/**
 * Class EventCategory
 * @package Salamek\MojeOlomouc\Model
 */
class EventCategory implements IEventCategory
{
    use TEntityIdentifier;
    
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
    public function __construct(string $title, bool $isVisible = null, $id = null)
    {
        $this->setTitle($title);
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
     * @param bool|null $isVisible
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
     * @return bool|null
     */
    public function getIsVisible(): ?bool
    {
        return $this->isVisible;
    }
}