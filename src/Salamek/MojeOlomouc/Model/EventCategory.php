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
    public function __construct(string $title, bool $isVisible = null, $id = null)
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

    /**
     * @return array
     */
    public function toPrimitiveArray(): array
    {
        $primitiveArray = [
            'title' => $this->title
        ];

        if (!is_null($this->isVisible)) $primitiveArray['isVisible'] = $this->isVisible;

        return $primitiveArray;
    }

    /**
     * @param array $modelData
     * @return EventCategory
     */
    public static function fromPrimitiveArray(array $modelData): EventCategory
    {
        return new EventCategory(
            $modelData['title'],
            (array_key_exists('isVisible', $modelData) ? $modelData['isVisible']: null),
            (array_key_exists('id', $modelData) ? $modelData['id']: null)
        );
    }
}