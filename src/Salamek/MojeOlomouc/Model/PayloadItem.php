<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Model;
use Salamek\MojeOlomouc\Enum\RequestActionCodeEnum;
use Salamek\MojeOlomouc\Exception\InvalidArgumentException;
use Salamek\MojeOlomouc\Validator\IntInArrayValidator;

/**
 * Class PayloadItem
 * @package Salamek\MojeOlomouc\Model
 */
class PayloadItem
{
    /** @var IModel */
    private $entity;

    /** @var int */
    private $action;

    /** @var string */
    private $dataKey;

    /** @var int|null */
    private $id;

    /**
     * PayloadItem constructor.
     * @param IModel $entity
     * @param string $dataKey
     * @param int $action
     */
    public function __construct(IModel $entity, string $dataKey, int $action)
    {
        $this->setEntity($entity);
        $this->setAction($action);
        $this->setDataKey($dataKey);

        // These actions require ID
        if (in_array($action, [
            RequestActionCodeEnum::ACTION_CODE_UPDATE,
            RequestActionCodeEnum::ACTION_CODE_DELETE
        ]) && is_null($entity->getId()))
        {
            throw new InvalidArgumentException('This action requires model to have set ID');
        }

        $this->setId($entity->getId());
    }

    /**
     * @param IModel $entity
     */
    public function setEntity(IModel $entity): void
    {
        $this->entity = $entity;
    }

    /**
     * @param int $action
     */
    public function setAction(int $action): void
    {
        IntInArrayValidator::validate($action, [
            RequestActionCodeEnum::ACTION_CODE_CREATE,
            RequestActionCodeEnum::ACTION_CODE_UPDATE,
            RequestActionCodeEnum::ACTION_CODE_DELETE,
        ]);
        $this->action = $action;
    }

    /**
     * @param string $dataKey
     */
    public function setDataKey(string $dataKey): void
    {
        $this->dataKey = $dataKey;
    }

    /**
     * @param int|null $id
     */
    public function setId($id = null): void
    {
        $this->id = $id;
    }

    /**
     * @return IModel
     */
    public function getEntity(): IModel
    {
        return $this->entity;
    }

    /**
     * @return int
     */
    public function getAction(): int
    {
        return $this->action;
    }

    /**
     * @return string
     */
    public function getDataKey()
    {
        return $this->dataKey;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function toPrimitiveArray(): array
    {
        $primitiveArray = [
            'action' => [
                'code' => $this->action,
                'id' => $this->id
            ]
        ];

        $primitiveArray[$this->dataKey] = $this->entity->toPrimitiveArray();

        return $primitiveArray;
    }
}