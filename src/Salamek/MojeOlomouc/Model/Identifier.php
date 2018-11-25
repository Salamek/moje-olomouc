<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Model;

/**
 * Class Identifier
 * @package Salamek\MojeOlomouc\Model
 */
class Identifier implements IModel
{
    use TEntityIdentifier;

    /**
     * Identifier constructor.
     * @param int $entityIdentifier
     */
    public function __construct(int $entityIdentifier)
    {
        $this->setEntityIdentifier($entityIdentifier);
    }

    /**
     * @return array
     */
    public function toPrimitiveArray(): array
    {
        return [
            'id' => $this->entityIdentifier
        ];
    }

    /**
     * @param array $modelData
     * @return Identifier
     */
    public static function fromPrimitiveArray(array $modelData)
    {
        return new Identifier($modelData['id']);
    }

}