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
    use TEntityIdentifier;

    /** @var string */
    private $title;

    /** @var int|null */
    private $consumerFlags;

    /** @var bool */
    private $isVisible;

    /**
     * PlaceCategory constructor.
     * @param string $title
     * @param int|null $consumerFlags
     * @param bool|null $isVisible
     * @param int|null $id
     */
    public function __construct(string $title, int $consumerFlags = null, bool $isVisible = null, int $id = null)
    {
        $this->setTitle($title);
        $this->setConsumerFlags($consumerFlags);
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
     * @param boolean|null $isVisible
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
    public function getIsVisible(): ?bool
    {
        return $this->isVisible;
    }

    /**
     * @return array
     */
    public function toPrimitiveArray(): array
    {
        // Required
        $primitiveArray = [
            'title' => $this->title,
        ];

        // Optional
        if (!is_null($this->consumerFlags)) $primitiveArray['consumerFlags'] = $this->consumerFlags;
        if (!is_null($this->isVisible)) $primitiveArray['isVisible'] = $this->isVisible;

        return $primitiveArray;
    }

    /**
     * @param array $modelData
     * @return PlaceCategory
     */
    public static function fromPrimitiveArray(array $modelData): PlaceCategory
    {
        return new PlaceCategory(
            $modelData['title'],
            (array_key_exists('consumerFlags', $modelData) ? $modelData['consumerFlags'] : null),
            (array_key_exists('isVisible', $modelData) ? $modelData['isVisible'] : null),
            (array_key_exists('id', $modelData) ? $modelData['id'] : null)
        );
    }
    
}