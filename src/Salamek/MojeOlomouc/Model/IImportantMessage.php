<?php

declare(strict_types=1);

namespace Salamek\MojeOlomouc\Model;

/**
 * Interface IImportantMessage
 * @package Salamek\MojeOlomouc\Model
 */
interface IImportantMessage
{
    /**
     * @param string $text
     */
    public function setText(string $text): void;

    /**
     * @param \DateTime $dateTimeAt
     */
    public function setDateTimeAt(\DateTime $dateTimeAt): void;

    /**
     * @param \DateTime $expireAt
     */
    public function setExpireAt(\DateTime $expireAt): void;

    /**
     * @param int $type
     */
    public function setType(int $type): void;

    /**
     * @param int $severity
     */
    public function setSeverity(int $severity): void;

    /**
     * @param bool $isVisible
     */
    public function setIsVisible(bool $isVisible): void;

    /**
     * @return string
     */
    public function getText(): string;

    /**
     * @return \DateTime
     */
    public function getDateTimeAt(): \DateTime;

    /**
     * @return \DateTime
     */
    public function getExpireAt(): \DateTime;

    /**
     * @return int
     */
    public function getType(): int;

    /**
     * @return int
     */
    public function getSeverity(): int;

    /**
     * @return bool
     */
    public function getIsVisible(): bool;
}