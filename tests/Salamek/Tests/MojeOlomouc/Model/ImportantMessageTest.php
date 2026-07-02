<?php

declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc\Model;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

use Salamek\MojeOlomouc\Enum\DateTime;
use Salamek\MojeOlomouc\Hydrator\IImportantMessage;
use Salamek\MojeOlomouc\Model\ImportantMessage;
use Salamek\Tests\MojeOlomouc\BaseTest;
use Salamek\MojeOlomouc\Enum\ImportantMessageSeverityEnum;
use Salamek\MojeOlomouc\Enum\ImportantMessageTypeEnum;


class ImportantMessageTest extends BaseTest
{
    /**
     * @param string $text
     * @param \DateTimeInterface $dateTimeAt
     * @param int $type
     * @param int $severity
     * @param \DateTimeInterface|null $expireAt
     * @param bool $isVisible
     * @param int|null $id
     */
#[Test]
#[DataProvider('provideValidConstructorParameters')]

    public function createRequiredShouldBeGoodTest(
        string $text,
        \DateTimeInterface $dateTimeAt,
        int $type = ImportantMessageTypeEnum::TRAFFIC_SITUATION,
        int $severity = ImportantMessageSeverityEnum::WARNING,
        ?\DateTimeInterface $expireAt = null,
        bool $isVisible = true,
        ?int $id = null
    )
    {
        $importantMessage = new ImportantMessage(
            $text,
            $dateTimeAt,
            $type,
            $severity
        );

        $this->assertEquals($text, $importantMessage->getText());
        $this->assertEquals($dateTimeAt, $importantMessage->getDateTimeAt());
        $this->assertEquals($type, $importantMessage->getType());
        $this->assertEquals($severity, $importantMessage->getSeverity());
        $this->assertEquals(null, $importantMessage->getExpireAt());
        $this->assertEquals(true, $importantMessage->getIsVisible());
        $this->assertEquals(null, $importantMessage->getEntityIdentifier());

    }


    /**
     * @param string $text
     * @param \DateTimeInterface $dateTimeAt
     * @param int $type
     * @param int $severity
     * @param \DateTimeInterface|null $expireAt
     * @param bool $isVisible
     * @param int|null $id
     */
#[Test]
#[DataProvider('provideValidConstructorParameters')]

    public function createOptionalShouldBeGoodTest(
        string $text,
        \DateTimeInterface $dateTimeAt,
        int $type = ImportantMessageTypeEnum::TRAFFIC_SITUATION,
        int $severity = ImportantMessageSeverityEnum::WARNING,
        ?\DateTimeInterface $expireAt = null,
        bool $isVisible = true,
        ?int $id = null
    )
    {
        $importantMessage = new ImportantMessage(
            $text,
            $dateTimeAt,
            $type,
            $severity,
            $expireAt,
            $isVisible,
            $id
        );

        $this->assertEquals($text, $importantMessage->getText());
        $this->assertEquals($dateTimeAt, $importantMessage->getDateTimeAt());
        $this->assertEquals($expireAt, $importantMessage->getExpireAt());
        $this->assertEquals($type, $importantMessage->getType());
        $this->assertEquals($severity, $importantMessage->getSeverity());
        $this->assertEquals($isVisible, $importantMessage->getIsVisible());
        $this->assertEquals($id, $importantMessage->getEntityIdentifier());
    }

    /**
     * @param string $text
     * @param \DateTimeInterface $dateTimeAt
     * @param int $type
     * @param int $severity
     * @param \DateTimeInterface|null $expireAt
     * @param bool $isVisible
     * @param int|null $id
     */
#[Test]
#[DataProvider('provideInvalidConstructorParameters')]

    public function createOptionalShouldFailOnBadData(
        string $text,
        \DateTimeInterface $dateTimeAt,
        int $type = ImportantMessageTypeEnum::TRAFFIC_SITUATION,
        int $severity = ImportantMessageSeverityEnum::WARNING,
        ?\DateTimeInterface $expireAt = null,
        bool $isVisible = true,
        ?int $id = null
    )
    {
        $this->expectException(\Salamek\MojeOlomouc\Exception\InvalidArgumentException::class);
        new ImportantMessage(
            $text,
            $dateTimeAt,
            $type,
            $severity,
            $expireAt,
            $isVisible,
            $id
        );
    }

    /**
     * @return array
     * @throws \Exception
     */

    public static function provideInvalidConstructorParameters(): array
    {
        return [
            [str_repeat('title-'.mt_rand(), 128), self::getDateTime(), ImportantMessageTypeEnum::TRAFFIC_SITUATION, ImportantMessageSeverityEnum::WARNING, self::getDateTime(), true, null],
            ['title-'.mt_rand(), self::getDateTime(), 128, ImportantMessageSeverityEnum::WARNING, self::getDateTime(), true, null],
            ['title-'.mt_rand(), self::getDateTime(), ImportantMessageTypeEnum::TRAFFIC_SITUATION, 192, self::getDateTime(), true, null],
        ];
    }


    /**
     * @return array
     * @throws \Exception
     */

    public static function provideValidConstructorParameters(): array
    {
        return [
            ['title-'.mt_rand(), self::getDateTime(), ImportantMessageTypeEnum::TRAFFIC_SITUATION, ImportantMessageSeverityEnum::WARNING, self::getDateTime(), true, null],
            ['title-'.mt_rand(), self::getDateTime(), ImportantMessageTypeEnum::WIND_CONDITIONS, ImportantMessageSeverityEnum::WARNING, self::getDateTime(), true, null],
            ['title-'.mt_rand(), self::getDateTime(), ImportantMessageTypeEnum::OTHER, ImportantMessageSeverityEnum::WARNING, self::getDateTime(), true, null],
            ['title-'.mt_rand(), self::getDateTime(), ImportantMessageTypeEnum::TRAFFIC_SITUATION, ImportantMessageSeverityEnum::DANGER, self::getDateTime(), true, null],
            ['title-'.mt_rand(), self::getDateTime(), ImportantMessageTypeEnum::TRAFFIC_SITUATION, ImportantMessageSeverityEnum::DANGER, self::getDateTime(), true, mt_rand()],
        ];
    }
}