<?php

declare(strict_types=1);

namespace Salamek\MojeOlomouc\Model;
use Salamek\MojeOlomouc\Enum\DateTime;
use Salamek\MojeOlomouc\Enum\ImportantMessageSeverityEnum;
use Salamek\MojeOlomouc\Enum\ImportantMessageTypeEnum;
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

    /** @var \DateTimeInterface */
    private $dateTimeAt;

    /** @var \DateTimeInterface|null */
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
     * @param \DateTimeInterface $dateTimeAt
     * @param int $type
     * @param int $severity
     * @param \DateTimeInterface|null $expireAt
     * @param bool $isVisible
     * @param int|null $id
     */
    public function __construct(
        string $text,
        \DateTimeInterface $dateTimeAt,
        int $type = ImportantMessageTypeEnum::TRAFFIC_SITUATION,
        int $severity = ImportantMessageSeverityEnum::WARNING,
        \DateTimeInterface $expireAt = null,
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
     * @param \DateTimeInterface $dateTimeAt
     */
    public function setDateTimeAt(\DateTimeInterface $dateTimeAt): void
    {
        $this->dateTimeAt = $dateTimeAt;
    }

    /**
     * @param \DateTimeInterface|null $expireAt
     */
    public function setExpireAt(\DateTimeInterface $expireAt = null): void
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
     * @return \DateTimeInterface
     */
    public function getDateTimeAt(): \DateTimeInterface
    {
        return $this->dateTimeAt;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getExpireAt(): ?\DateTimeInterface
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
        $primitiveArray = [
            'text' => $this->text,
            'dateTimeAt'   => $this->dateTimeAt->format(DateTime::NOT_A_ISO8601),
            'type'   => $this->type,
            'severity'   => $this->severity,
            'isVisible'   => $this->isVisible
        ];

        if (!is_null($this->expireAt)) $primitiveArray['expireAt'] = $this->expireAt->format(DateTime::NOT_A_ISO8601);

        return $primitiveArray;
    }

    /**
     * @param array $modelData
     * @return ImportantMessage
     */
    public static function fromPrimitiveArray(array $modelData): ImportantMessage
    {
        return new ImportantMessage(
            $modelData['text'],
            \DateTime::createFromFormat(DateTime::NOT_A_ISO8601, $modelData['dateTimeAt']),
            (array_key_exists('type', $modelData) ? $modelData['type'] : ImportantMessageTypeEnum::TRAFFIC_SITUATION),
            (array_key_exists('severity', $modelData) ? $modelData['severity'] : ImportantMessageSeverityEnum::WARNING),
            (array_key_exists('expireAt', $modelData) ? \DateTime::createFromFormat(DateTime::NOT_A_ISO8601, $modelData['expireAt']) : null),
            (array_key_exists('isVisible', $modelData) ? $modelData['isVisible'] : true),
            (array_key_exists('id', $modelData) ? $modelData['id'] : null)
        );
    }

}