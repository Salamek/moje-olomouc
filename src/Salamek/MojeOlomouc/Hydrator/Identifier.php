<?php
/**
 * Created by PhpStorm.
 * User: sadam
 * Date: 11/27/18
 * Time: 10:57 PM
 */

namespace Salamek\MojeOlomouc\Hydrator;

/**
 * Class Identifier
 * @package Salamek\MojeOlomouc\Hydrator
 */
class Identifier implements IIdentifier
{
    /**
     * @var string
     */
    private $modelClass;

    /**
     * Article constructor.
     * @param string $modelClass
     */
    public function __construct(string $modelClass)
    {
        $this->modelClass = $modelClass;
    }

    /**
     * @param \Salamek\MojeOlomouc\Model\IModel $model
     * @return array
     */
    public function toPrimitiveArray(\Salamek\MojeOlomouc\Model\IModel $model): array
    {
        return [
            'id' => $model->getEntityIdentifier()
        ];
    }

    /**
     * @param array $modelData
     * @return mixed|\Salamek\MojeOlomouc\Model\IModel
     */
    public function fromPrimitiveArray(array $modelData)
    {
        return new $this->modelClass($modelData['id']);
    }

}