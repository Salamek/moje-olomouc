<?php

declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc\Model;

use Salamek\MojeOlomouc\Enum\DateTime;
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
     * @param \DateTimeInterface $expireAt
     * @param bool $isVisible
     * @param int|null $id
     */
    public function createRequiredShouldBeGoodTest(
        string $text,
        \DateTimeInterface $dateTimeAt,
        int $type = ImportantMessageTypeEnum::TRAFFIC_SITUATION,
        int $severity = ImportantMessageSeverityEnum::WARNING,
        \DateTimeInterface $expireAt,
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
        $this->assertEquals(null, $importantMessage->getId());
        $this->assertInternalType('array', $importantMessage->toPrimitiveArray());

        $primitiveArrayTest = [
            'text' => $text,
            'dateTimeAt'   => $dateTimeAt->format(DateTime::NOT_A_ISO8601),
            'type'   => $type,
            'severity'   => $severity,
            'isVisible'   => $importantMessage->getIsVisible()
        ];

        $this->assertEquals($primitiveArrayTest, $importantMessage->toPrimitiveArray());
    }


    /**
     * @test
     * @dataProvider provideValidConstructorParameters
     * @param string $text
     * @param \DateTimeInterface $dateTimeAt
     * @param int $type
     * @param int $severity
     * @param \DateTimeInterface $expireAt
     * @param bool $isVisible
     * @param int|null $id
     */
    public function createOptionalShouldBeGoodTest(
        string $text,
        \DateTimeInterface $dateTimeAt,
        int $type = ImportantMessageTypeEnum::TRAFFIC_SITUATION,
        int $severity = ImportantMessageSeverityEnum::WARNING,
        \DateTimeInterface $expireAt,
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
        $this->assertEquals($id, $importantMessage->getId());
        $this->assertInternalType('array', $importantMessage->toPrimitiveArray());


        $primitiveArrayTest = [
            'text' => $text,
            'dateTimeAt'   => $dateTimeAt->format(DateTime::NOT_A_ISO8601),
            'type'   => $type,
            'severity'   => $severity,
            'expireAt'  => $expireAt->format(DateTime::NOT_A_ISO8601),
            'isVisible'   => $isVisible
        ];

        $this->assertEquals($primitiveArrayTest, $importantMessage->toPrimitiveArray());
    }

    /**
     * @test
     * @dataProvider provideInvalidConstructorParameters
     * @expectedException Salamek\MojeOlomouc\Exception\InvalidArgumentException
     * @param string $text
     * @param \DateTimeInterface $dateTimeAt
     * @param int $type
     * @param int $severity
     * @param \DateTimeInterface $expireAt
     * @param bool $isVisible
     * @param int|null $id
     */
    public function createOptionalShouldFailOnBadData(
        string $text,
        \DateTimeInterface $dateTimeAt,
        int $type = ImportantMessageTypeEnum::TRAFFIC_SITUATION,
        int $severity = ImportantMessageSeverityEnum::WARNING,
        \DateTimeInterface $expireAt,
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
     * @test
     * @dataProvider provideValidConstructorParameters
     * @param string $text
     * @param \DateTimeInterface $dateTimeAt
     * @param int $type
     * @param int $severity
     * @param \DateTimeInterface $expireAt
     * @param bool $isVisible
     * @param int|null $id
     */
    public function createFromRequiredPrimitiveArrayShouldBeGood(
        string $text,
        \DateTimeInterface $dateTimeAt,
        int $type = ImportantMessageTypeEnum::TRAFFIC_SITUATION,
        int $severity = ImportantMessageSeverityEnum::WARNING,
        \DateTimeInterface $expireAt,
        bool $isVisible = true,
        int $id = null
    )
    {
        $place = ImportantMessage::fromPrimitiveArray(
            [
                'text' => $text,
                'dateTimeAt' => $dateTimeAt->format(DateTime::NOT_A_ISO8601),
                'type' => $type,
                'severity' => $severity
            ]
        );

        $this->assertEquals($text, $place->getText());
        $this->assertEquals($dateTimeAt, $place->getDateTimeAt());
        $this->assertEquals($type, $place->getType());
        $this->assertEquals($severity, $place->getSeverity());
        $this->assertEquals(null, $place->getExpireAt());
        $this->assertEquals(true, $place->getIsVisible());
        $this->assertEquals(null, $place->getId());
    }

    /**
     * @test
     * @dataProvider provideValidConstructorParameters
     * @param string $text
     * @param \DateTimeInterface $dateTimeAt
     * @param int $type
     * @param int $severity
     * @param \DateTimeInterface $expireAt
     * @param bool $isVisible
     * @param int|null $id
     */
    public function createFromOptionalPrimitiveArrayShouldBeGood(
        string $text,
        \DateTimeInterface $dateTimeAt,
        int $type = ImportantMessageTypeEnum::TRAFFIC_SITUATION,
        int $severity = ImportantMessageSeverityEnum::WARNING,
        \DateTimeInterface $expireAt,
        bool $isVisible = true,
        int $id = null
    )
    {
        $place = ImportantMessage::fromPrimitiveArray(
            [
                'text' => $text,
                'dateTimeAt' => $dateTimeAt->format(DateTime::NOT_A_ISO8601),
                'type' => $type,
                'severity' => $severity,
                'expireAt' => $expireAt->format(DateTime::NOT_A_ISO8601),
                'isVisible' => $isVisible,
                'id' => $id
            ]
        );

        $this->assertEquals($text, $place->getText());
        $this->assertEquals($dateTimeAt, $place->getDateTimeAt());
        $this->assertEquals($type, $place->getType());
        $this->assertEquals($severity, $place->getSeverity());
        $this->assertEquals($expireAt, $place->getExpireAt());
        $this->assertEquals($isVisible, $place->getIsVisible());
        $this->assertEquals($id, $place->getId());
    }

    /**
     * @return array
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