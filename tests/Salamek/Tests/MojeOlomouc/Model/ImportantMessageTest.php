<?php

declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc\Model;

use Salamek\MojeOlomouc\Enum\DateTime;
use Salamek\MojeOlomouc\Hydrator\IImportantMessage;
use Salamek\MojeOlomouc\Model\ImportantMessage;
use Salamek\Tests\MojeOlomouc\BaseTest;
use Salamek\MojeOlomouc\Enum\ImportantMessageSeverityEnum;
use Salamek\MojeOlomouc\Enum\ImportantMessageTypeEnum;


class ImportantMessageTest extends BaseTest
{
    /**
     * @test
     * @dataProvider provideValidConstructorParameters
     * @param string $text
     * @param \DateTimeInterface $dateTimeAt
     * @param int $type
     * @param int $severity
     * @param \DateTimeInterface|null $expireAt
     * @param bool $isVisible
     * @param int|null $id
     */
    public function createRequiredShouldBeGoodTest(
        string $text,
        \DateTimeInterface $dateTimeAt,
        int $type = ImportantMessageTypeEnum::TRAFFIC_SITUATION,
        int $severity = ImportantMessageSeverityEnum::WARNING,
        \DateTimeInterface $expireAt = null,
        bool $isVisible = true,
        int $id = null
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
     * @test
     * @dataProvider provideValidConstructorParameters
     * @param string $text
     * @param \DateTimeInterface $dateTimeAt
     * @param int $type
     * @param int $severity
     * @param \DateTimeInterface|null $expireAt
     * @param bool $isVisible
     * @param int|null $id
     */
    public function createOptionalShouldBeGoodTest(
        string $text,
        \DateTimeInterface $dateTimeAt,
        int $type = ImportantMessageTypeEnum::TRAFFIC_SITUATION,
        int $severity = ImportantMessageSeverityEnum::WARNING,
        \DateTimeInterface $expireAt = null,
        bool $isVisible = true,
        int $id = null
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
     * @test
     * @dataProvider provideInvalidConstructorParameters
     * @expectedException \Salamek\MojeOlomouc\Exception\InvalidArgumentException
     * @param string $text
     * @param \DateTimeInterface $dateTimeAt
     * @param int $type
     * @param int $severity
     * @param \DateTimeInterface|null $expireAt
     * @param bool $isVisible
     * @param int|null $id
     */
    public function createOptionalShouldFailOnBadData(
        string $text,
        \DateTimeInterface $dateTimeAt,
        int $type = ImportantMessageTypeEnum::TRAFFIC_SITUATION,
        int $severity = ImportantMessageSeverityEnum::WARNING,
        \DateTimeInterface $expireAt = null,
        bool $isVisible = true,
        int $id = null
    )
    {
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
    public function provideInvalidConstructorParameters(): array
    {
        return [
            [str_repeat('title-'.mt_rand(), 128), $this->getDateTime(), ImportantMessageTypeEnum::TRAFFIC_SITUATION, ImportantMessageSeverityEnum::WARNING, $this->getDateTime(), true, null],
            ['title-'.mt_rand(), $this->getDateTime(), 128, ImportantMessageSeverityEnum::WARNING, $this->getDateTime(), true, null],
            ['title-'.mt_rand(), $this->getDateTime(), ImportantMessageTypeEnum::TRAFFIC_SITUATION, 192, $this->getDateTime(), true, null],
        ];
    }


    /**
     * @return array
     * @throws \Exception
     */
    public function provideValidConstructorParameters(): array
    {
        return [
            ['title-'.mt_rand(), $this->getDateTime(), ImportantMessageTypeEnum::TRAFFIC_SITUATION, ImportantMessageSeverityEnum::WARNING, $this->getDateTime(), true, null],
            ['title-'.mt_rand(), $this->getDateTime(), ImportantMessageTypeEnum::WIND_CONDITIONS, ImportantMessageSeverityEnum::WARNING, $this->getDateTime(), true, null],
            ['title-'.mt_rand(), $this->getDateTime(), ImportantMessageTypeEnum::OTHER, ImportantMessageSeverityEnum::WARNING, $this->getDateTime(), true, null],
            ['title-'.mt_rand(), $this->getDateTime(), ImportantMessageTypeEnum::TRAFFIC_SITUATION, ImportantMessageSeverityEnum::DANGER, $this->getDateTime(), true, null],
            ['title-'.mt_rand(), $this->getDateTime(), ImportantMessageTypeEnum::TRAFFIC_SITUATION, ImportantMessageSeverityEnum::DANGER, $this->getDateTime(), true, mt_rand()],
        ];
    }
}