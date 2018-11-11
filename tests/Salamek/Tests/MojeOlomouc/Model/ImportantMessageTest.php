<?php

declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc\Model;

use Salamek\MojeOlomouc\Enum\ArticleApproveStateEnum;
use Salamek\MojeOlomouc\Model\Article;
use Salamek\MojeOlomouc\Model\EntityImage;
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
     * @param \DateTime $dateTimeAt
     * @param \DateTime $expireAt
     * @param int $type
     * @param int $severity
     * @param bool $isVisible
     * @param int|null $id
     */
    public function createRequiredShouldBeGoodTest(
        string $text,
        \DateTime $dateTimeAt,
        \DateTime $expireAt,
        int $type = ImportantMessageTypeEnum::TRAFFIC_SITUATION,
        int $severity = ImportantMessageSeverityEnum::WARNING,
        bool $isVisible = true,
        int $id = null
    )
    {
        $importantMessage = new ImportantMessage(
            $text,
            $dateTimeAt,
            $expireAt
        );

        $this->assertEquals($text, $importantMessage->getText());
        $this->assertEquals($dateTimeAt, $importantMessage->getDateTimeAt());
        $this->assertEquals($expireAt, $importantMessage->getExpireAt());
        $this->assertEquals(ImportantMessageTypeEnum::TRAFFIC_SITUATION, $importantMessage->getType());
        $this->assertEquals(ImportantMessageSeverityEnum::WARNING, $importantMessage->getSeverity());
        $this->assertEquals(true, $importantMessage->getIsVisible());
        $this->assertEquals(null, $importantMessage->getId());
        $this->assertInternalType('array', $importantMessage->toPrimitiveArray());

        $primitiveArrayTest = [
            'text' => $text,
            'dateTimeAt'   => $dateTimeAt->format(\DateTime::ISO8601),
            'expireAt'  => $expireAt->format(\DateTime::ISO8601),
            'type'   => $importantMessage->getType(),
            'severity'   => $importantMessage->getSeverity(),
            'isVisible'   => $importantMessage->getIsVisible()
        ];

        $this->assertEquals($primitiveArrayTest, $importantMessage->toPrimitiveArray());
    }


    /**
     * @test
     * @dataProvider provideValidConstructorParameters
     * @param string $text
     * @param \DateTime $dateTimeAt
     * @param \DateTime $expireAt
     * @param int $type
     * @param int $severity
     * @param bool $isVisible
     * @param int|null $id
     */
    public function createOptionalShouldBeGoodTest(
        string $text,
        \DateTime $dateTimeAt,
        \DateTime $expireAt,
        int $type = ImportantMessageTypeEnum::TRAFFIC_SITUATION,
        int $severity = ImportantMessageSeverityEnum::WARNING,
        bool $isVisible = true,
        int $id = null
    )
    {
        $importantMessage = new ImportantMessage(
            $text,
            $dateTimeAt,
            $expireAt,
            $type,
            $severity,
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
            'dateTimeAt'   => $dateTimeAt->format(\DateTime::ISO8601),
            'expireAt'  => $expireAt->format(\DateTime::ISO8601),
            'type'   => $type,
            'severity'   => $severity,
            'isVisible'   => $isVisible
        ];

        $this->assertEquals($primitiveArrayTest, $importantMessage->toPrimitiveArray());
    }

    /**
     * @test
     * @dataProvider provideInvalidConstructorParameters
     * @expectedException Salamek\MojeOlomouc\Exception\InvalidArgumentException
     * @param string $text
     * @param \DateTime $dateTimeAt
     * @param \DateTime $expireAt
     * @param int $type
     * @param int $severity
     * @param bool $isVisible
     * @param int|null $id
     */
    public function createOptionalShouldFailOnBadData(
        string $text,
        \DateTime $dateTimeAt,
        \DateTime $expireAt,
        int $type = ImportantMessageTypeEnum::TRAFFIC_SITUATION,
        int $severity = ImportantMessageSeverityEnum::WARNING,
        bool $isVisible = true,
        int $id = null
    )
    {
        new ImportantMessage(
            $text,
            $dateTimeAt,
            $expireAt,
            $type,
            $severity,
            $isVisible,
            $id
        );
    }

    /**
     * @return array
     */
    public function provideInvalidConstructorParameters(): array
    {
        return [
            [str_repeat('title-'.mt_rand(), 128), new \DateTime(), new \DateTime(), ImportantMessageTypeEnum::TRAFFIC_SITUATION, ImportantMessageSeverityEnum::WARNING, true, null],
            ['title-'.mt_rand(), new \DateTime(), new \DateTime(), 128, ImportantMessageSeverityEnum::WARNING, true, null],
            ['title-'.mt_rand(), new \DateTime(), new \DateTime(), ImportantMessageTypeEnum::TRAFFIC_SITUATION, 192, true, null],
        ];
    }


    /**
     * @return array
     */
    public function provideValidConstructorParameters(): array
    {
        return [
            ['title-'.mt_rand(), new \DateTime(), new \DateTime(), ImportantMessageTypeEnum::TRAFFIC_SITUATION, ImportantMessageSeverityEnum::WARNING, true, null],
            ['title-'.mt_rand(), new \DateTime(), new \DateTime(), ImportantMessageTypeEnum::WIND_CONDITIONS, ImportantMessageSeverityEnum::WARNING, true, null],
            ['title-'.mt_rand(), new \DateTime(), new \DateTime(), ImportantMessageTypeEnum::OTHER, ImportantMessageSeverityEnum::WARNING, true, null],
            ['title-'.mt_rand(), new \DateTime(), new \DateTime(), ImportantMessageTypeEnum::TRAFFIC_SITUATION, ImportantMessageSeverityEnum::DANGER, true, null],
            ['title-'.mt_rand(), new \DateTime(), new \DateTime(), ImportantMessageTypeEnum::TRAFFIC_SITUATION, ImportantMessageSeverityEnum::DANGER, true, mt_rand()],
        ];
    }
}