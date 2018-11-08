<?php

declare(strict_types=1);

namespace Salamek\MojeOlomouc\Model;
use Salamek\MojeOlomouc\Exception\ImportantMessageSeverityEnum;
use Salamek\MojeOlomouc\Exception\ImportantMessageTypeEnum;
use Salamek\MojeOlomouc\Validator\IntInArrayValidator;
use Salamek\MojeOlomouc\Validator\MaxLengthValidator;

/**
 * Class ImportantMessage
 * @package Salamek\MojeOlomouc\Model
 */
class ImportantMessage implements IImportantMessage
{
    use TIdentifier;

    /** @var string */
    private $text;

    /** @var \DateTime */
    private $dateTimeAt;

    /** @var \DateTime */
    private $expireAt;

    /** @var int */
    private $type;

    /** @var int */
    private $severity;

    /** @var bool */
    private $isVisible;

    /**
     * ImportantMessage constructor.
     * @param string $text
     * @param \DateTime $dateTimeAt
     * @param \DateTime $expireAt
     * @param int $type
     * @param int $severity
     * @param bool $isVisible
     * @param int|null $id
     */
    public function __construct(
        string $text,
        \DateTime $dateTimeAt,
        \DateTime $expireAt,
        int $type = ImportantMessageTypeEnum::TRAFFIC_SITUATION,
        int $severity = ImportantMessageSeverityEnum::WARNING,
        bool $isVisible = true,
        int $id = null
    )
    {
        $this->setText($text);
        $this->setDateTimeAt($dateTimeAt);
        $this->setExpireAt($expireAt);
        $this->setType($type);
        $this->setSeverity($severity);
        $this->setIsVisible($isVisible);
        $this->setId($id);
    }

    /**
     * @param string $text
     */
    public function setText(string $text): void
    {
        MaxLengthValidator::validate($text, 500);
        $this->text = $text;
    }

    /**
     * @param \DateTime $dateTimeAt
     */
    public function setDateTimeAt(\DateTime $dateTimeAt): void
    {
        $this->dateTimeAt = $dateTimeAt;
    }

    /**
     * @param \DateTime $expireAt
     */
    public function setExpireAt(\DateTime $expireAt): void
    {
        $this->expireAt = $expireAt;
    }

    /**
     * @param int $type
     */
    public function setType(int $type): void
    {
        IntInArrayValidator::validate($type, [
            ImportantMessageTypeEnum::TRAFFIC_SITUATION,
            ImportantMessageTypeEnum::WIND_CONDITIONS,
            ImportantMessageTypeEnum::OTHER
        ]);
        $this->type = $type;
    }

    /**
     * @param int $severity
     */
    public function setSeverity(int $severity): void
    {
        IntInArrayValidator::validate($severity, [
            ImportantMessageSeverityEnum::WARNING,
            ImportantMessageSeverityEnum::DANGER
        ]);
        $this->severity = $severity;
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
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return \DateTime
     */
    public function getDateTimeAt(): \DateTime
    {
        return $this->dateTimeAt;
    }

    /**
     * @return \DateTime
     */
    public function getExpireAt(): \DateTime
    {
        return $this->expireAt;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getSeverity(): int
    {
        return $this->severity;
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
            'text' => $this->text,
            'dateTimeAt'   => $this->dateTimeAt->format(\DateTime::ISO8601),
            'expireAt'  => $this->expireAt->format(\DateTime::ISO8601),
            'type'   => $this->type,
            'severity'   => $this->severity,
            'isVisible'   => $this->isVisible
        ];
    }

}