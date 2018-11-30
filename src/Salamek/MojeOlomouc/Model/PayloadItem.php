<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Model;
use Salamek\MojeOlomouc\Enum\RequestActionCodeEnum;
use Salamek\MojeOlomouc\Exception\InvalidArgumentException;
use Salamek\MojeOlomouc\Hydrator\IHydrator;
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

    /** @var IHydrator */
    private $hydrator;

    /**
     * PayloadItem constructor.
     * @param IModel $entity
     * @param string $dataKey
     * @param int $action
     * @param IHydrator $hydrator
     */
    public function __construct(IModel $entity, string $dataKey, int $action, IHydrator $hydrator)
    {
        $this->setEntity($entity);
        $this->setAction($action);
        $this->setDataKey($dataKey);
        $this->setHydrator($hydrator);

        // These actions require ID
        if (in_array($action, [
            RequestActionCodeEnum::ACTION_CODE_UPDATE,
            RequestActionCodeEnum::ACTION_CODE_DELETE
        ]) && is_null($entity->getEntityIdentifier()))
        {
            throw new InvalidArgumentException('This action requires model to have set ID');
        }

        $this->setId($entity->getEntityIdentifier());
    }

    /**
     * @param IHydrator $hydrator
     */
    public function setHydrator(IHydrator $hydrator): void
    {
        $this->hydrator = $hydrator;
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

        $primitiveArray[$this->dataKey] = $this->hydrator->toPrimitiveArray($this->entity);

        return $primitiveArray;
    }
}