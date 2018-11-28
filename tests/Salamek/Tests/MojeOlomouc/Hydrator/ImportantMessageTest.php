<?php

declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc\Hydrator;


use Salamek\MojeOlomouc\Enum\DateTime;
use Salamek\MojeOlomouc\Enum\ImportantMessageSeverityEnum;
use Salamek\MojeOlomouc\Enum\ImportantMessageTypeEnum;
use Salamek\MojeOlomouc\Hydrator\IImportantMessage;
use Salamek\Tests\MojeOlomouc\BaseTest;

/**
 * Class ImportantMessageTest
 * @package Salamek\Tests\MojeOlomouc\Hydrator
 */
class ImportantMessageTest extends BaseTest
{
    /** @var IImportantMessage */
    private $hydrator;

    public function setUp()
    {
        parent::setUp();

        $this->hydrator = $this->getHydrator(IImportantMessage::class);
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
    public function createFromRequiredPrimitiveArrayShouldBeGood(
        string $text,
        \DateTimeInterface $dateTimeAt,
        int $type = ImportantMessageTypeEnum::TRAFFIC_SITUATION,
        int $severity = ImportantMessageSeverityEnum::WARNING,
        \DateTimeInterface $expireAt = null,
        bool $isVisible = true,
        int $id = null
    )
    {
        $place = $this->hydrator->fromPrimitiveArray(
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
        $this->assertEquals(null, $place->getEntityIdentifier());
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
        \DateTimeInterface $expireAt = null,
        bool $isVisible = true,
        int $id = null
    )
    {
        $place = $this->hydrator->fromPrimitiveArray(
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
        $this->assertEquals($id, $place->getEntityIdentifier());
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