<?php

declare(strict_types=1);

namespace Salamek\MojeOlomouc\Hydrator;
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
    /**
     * @var string
     */
    private $modelClass;

    /**
     * ImportantMessage constructor.
     * @param string $modelClass
     */
    public function __construct(string $modelClass)
    {
        $this->modelClass = $modelClass;
    }

    /**
     * @param \Salamek\MojeOlomouc\Model\IImportantMessage $importantMessage
     * @return array
     */
    public function toPrimitiveArray(\Salamek\MojeOlomouc\Model\IImportantMessage $importantMessage): array
    {
        $primitiveArray = [
            'text' => $importantMessage->getText(),
            'dateTimeAt'   => $importantMessage->getDateTimeAt()->format(DateTime::NOT_A_ISO8601),
            'type'   => $importantMessage->getType(),
            'severity'   => $importantMessage->getSeverity(),
            'isVisible'   => $importantMessage->getIsVisible()
        ];

        if (!is_null($importantMessage->getExpireAt())) $primitiveArray['expireAt'] = $importantMessage->getExpireAt()->format(DateTime::NOT_A_ISO8601);

        return $primitiveArray;
    }

    /**
     * @param array $modelData
     * @return \Salamek\MojeOlomouc\Model\IImportantMessage
     */
    public function fromPrimitiveArray(array $modelData): \Salamek\MojeOlomouc\Model\IImportantMessage
    {
        return new $this->modelClass(
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