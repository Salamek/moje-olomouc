<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Model;

/**
 * Class Identifier
 * @package Salamek\MojeOlomouc\Model
 */
class Identifier implements IModel
{
    use TIdentifier;

    /**
     * Identifier constructor.
     * @param int $id
     */
    public function __construct(int $id)
    {
        $this->setId($id);
    }

    /**
     * @return array
     */
    public function toPrimitiveArray(): array
    {
        return [
            'id' => $this->id
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