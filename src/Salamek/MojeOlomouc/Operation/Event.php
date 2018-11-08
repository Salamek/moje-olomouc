<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Operation;

use Salamek\MojeOlomouc\Exception\EventApproveStateEnum;
use Salamek\MojeOlomouc\Exception\EventFeaturedLevelEnum;
use Salamek\MojeOlomouc\Exception\ImportantMessageSeverityEnum;
use Salamek\MojeOlomouc\Exception\ImportantMessageTypeEnum;
use Salamek\MojeOlomouc\Request;
use Salamek\MojeOlomouc\Response;
use Salamek\MojeOlomouc\Validator\GpsStringValidator;
use Salamek\MojeOlomouc\Validator\IntInArrayValidator;
use Salamek\MojeOlomouc\Validator\MaxLengthValidator;

/**
 * Class Event
 * @package Salamek\MojeOlomouc\Operation
 */
class Event implements IOperation
{
    /** @var Request */
    private $request;

    /**
     * Event constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param \DateTime|null $fromUpdatedAt
     * @param bool $showDeleted
     * @param bool $onlyApproved
     * @param bool $onlyVisible
     * @param bool $extraFields
     * @return Response
     */
    public function getAll(
        \DateTime $fromUpdatedAt = null,
        bool $showDeleted = false,
        bool $onlyApproved = true,
        bool $onlyVisible = true,
        bool $extraFields = false
    ): Response
    {
        $data = [
            'fromUpdatedAt' => ($fromUpdatedAt ? $fromUpdatedAt->format(\DateTime::ISO8601) : null),
            'showDeleted' => $showDeleted,
            'onlyApproved' => $onlyApproved,
            'onlyVisible' => $onlyVisible,
            'extraFields' => $extraFields,
        ];

        return $this->request->get('/api/export/events', $data);
    }

    public function create(
        string $title,
        string $description,
        \DateTime $startAt,
        \DateTime $endAt,
        string $placeDesc,
        string $placeLat,
        string $placeLon,
        array $categoryIdsArr,
        array $images = [], //@TODO ImportEntityImage
        string $attachmentUrl = null,
        string $fee = null,
        string $webUrl = null,
        string $facebookUrl = null,
        int $consumerFlags = null,
        bool $isVisible = true,
        int $approveState = null,
        int $featuredLevel = null
    ): Response
    {
        MaxLengthValidator::validate($title, 150);
        MaxLengthValidator::validate($description, 65535); // Not defined in docs. i assume TEXT col type
        MaxLengthValidator::validate($placeDesc, 300);
        GpsStringValidator::validate($placeLat);
        GpsStringValidator::validate($placeLon);


        if (!is_null($attachmentUrl))
        {
            MaxLengthValidator::validate($attachmentUrl, 65535); // Not defined in docs. i assume TEXT col type
        }

        if (!is_null($fee))
        {
            MaxLengthValidator::validate($fee, 50);
        }

        if (!is_null($webUrl))
        {
            MaxLengthValidator::validate($webUrl, 300);
        }

        if (!is_null($facebookUrl))
        {
            MaxLengthValidator::validate($facebookUrl, 300);
        }

        if (!is_null($approveState))
        {
            IntInArrayValidator::validate($approveState, [
                EventApproveStateEnum::APPROVED,
                EventApproveStateEnum::WAITING_FOR_ADD,
                EventApproveStateEnum::WAITING_FOR_UPDATE,
                EventApproveStateEnum::WAITING_FOR_DELETE
            ]);
        }

        if (!is_null($featuredLevel))
        {
            IntInArrayValidator::validate($featuredLevel, [
                EventFeaturedLevelEnum::STANDARD_RECOMMENDATION,
                EventFeaturedLevelEnum::EDITORIAL_TIP
            ]);
        }

        $data = [
            'event' => [
                'title' => $title,
                'description' => $description,
                'startAt' => ($startAt ? $startAt->format(\DateTime::ISO8601): null),
                'endAt' => ($endAt ? $endAt->format(\DateTime::ISO8601): null),
                'placeDesc' => $placeDesc,
                'placeLat' => $placeLat,
                'placeLon' => $placeLon,
                'categoryIdsArr'   => $categoryIdsArr,
                'images'   => $images, //@TODO
                'attachmentUrl'  => $attachmentUrl,
                'fee'   => $fee,
                'webUrl'   => $webUrl,
                'facebookUrl'   => $facebookUrl,
                'consumerFlags'   => $consumerFlags,
                'isVisible'   => $isVisible,
                'approveState'   => $approveState,
                'featuredLevel'   => $featuredLevel
            ]
        ];

        return $this->request->create('/api/import/events', $data);
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