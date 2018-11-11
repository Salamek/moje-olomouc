<?php

declare(strict_types=1);

namespace Salamek\MojeOlomouc\Model;

/**
 * Interface IImportantMessage
 * @package Salamek\MojeOlomouc\Model
 */
interface IImportantMessage extends IModel
{
    /**
     * @param string $text
     */
    public function setText(string $text): void;

    /**
     * @param \DateTimeInterface $dateTimeAt
     */
    public function setDateTimeAt(\DateTimeInterface $dateTimeAt): void;

    /**
     * @param \DateTimeInterface $expireAt
     */
    public function setExpireAt(\DateTimeInterface $expireAt): void;

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
     * @return \DateTimeInterface
     */
    public function getDateTimeAt(): \DateTimeInterface;

    /**
     * @return \DateTimeInterface
     */
    public function getExpireAt(): \DateTimeInterface;

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