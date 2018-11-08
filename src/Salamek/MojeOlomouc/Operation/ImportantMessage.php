<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Operation;

use Salamek\MojeOlomouc\Exception\ImportantMessageSeverityEnum;
use Salamek\MojeOlomouc\Exception\ImportantMessageTypeEnum;
use Salamek\MojeOlomouc\Request;
use Salamek\MojeOlomouc\Response;
use Salamek\MojeOlomouc\Validator\IntInArrayValidator;
use Salamek\MojeOlomouc\Validator\MaxLengthValidator;

/**
 * Class ImportantMessage
 * @package Salamek\MojeOlomouc\Operation
 */
class ImportantMessage implements IOperation
{
    /** @var Request */
    private $request;

    /**
     * ImportantMessage constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return Response
     * @TODO filters
     * @TODO is there getter for all important-messages ?
     */
    public function getAll(): Response
    {
        return $this->request->get('/api/export/important-messages');
    }

    /**
     * @param string $text
     * @param \DateTime $dateTimeAt
     * @param \DateTime $expireAt
     * @param int $type
     * @param int $severity
     * @param bool $isVisible
     * @return Response
     */
    public function create(
        string $text,
        \DateTime $dateTimeAt,
        \DateTime $expireAt,
        int $type = ImportantMessageTypeEnum::TRAFFIC_SITUATION,
        int $severity = ImportantMessageSeverityEnum::WARNING,
        bool $isVisible = true
    ): Response
    {
        MaxLengthValidator::validate($text, 500);

        IntInArrayValidator::validate($type, [
            ImportantMessageTypeEnum::TRAFFIC_SITUATION,
            ImportantMessageTypeEnum::WIND_CONDITIONS,
            ImportantMessageTypeEnum::OTHER
        ]);

        IntInArrayValidator::validate($severity, [
            ImportantMessageSeverityEnum::WARNING,
            ImportantMessageSeverityEnum::DANGER
        ]);

        $data = [
            'importantMessage' => [
                'text' => $text,
                'dateTimeAt'   => $dateTimeAt->format(\DateTime::ISO8601),
                'expireAt'  => $expireAt->format(\DateTime::ISO8601),
                'type'   => $type,
                'severity'   => $severity,
                'isVisible'   => $isVisible
            ]
        ];

        return $this->request->create('/api/import/important-messages', $data);
    }

    /**
     * @param int $id
     * @param string $text
     * @param \DateTime $dateTimeAt
     * @param \DateTime $expireAt
     * @param int $type
     * @param int $severity
     * @param bool $isVisible
     * @return Response
     */
    public function update(
        int $id,
        string $text,
        \DateTime $dateTimeAt,
        \DateTime $expireAt,
        int $type = ImportantMessageTypeEnum::TRAFFIC_SITUATION,
        int $severity = ImportantMessageSeverityEnum::WARNING,
        bool $isVisible = true
    ): Response
    {
        MaxLengthValidator::validate($text, 500);

        IntInArrayValidator::validate($type, [
            ImportantMessageTypeEnum::TRAFFIC_SITUATION,
            ImportantMessageTypeEnum::WIND_CONDITIONS,
            ImportantMessageTypeEnum::OTHER
        ]);

        IntInArrayValidator::validate($severity, [
            ImportantMessageSeverityEnum::WARNING,
            ImportantMessageSeverityEnum::DANGER
        ]);

        $data = [
            'importantMessage' => [
                'text' => $text,
                'dateTimeAt'   => $dateTimeAt->format(\DateTime::ISO8601),
                'expireAt'  => $expireAt->format(\DateTime::ISO8601),
                'type'   => $type,
                'severity'   => $severity,
                'isVisible'   => $isVisible
            ]
        ];

        return $this->request->update('/api/import/important-messages', $id, $data);
    }

    /**
     * @param int $id
     * @return Response
     */
    public function delete(int $id): Response
    {
        return $this->request->delete('/api/import/important-messages', $id);
    }
}