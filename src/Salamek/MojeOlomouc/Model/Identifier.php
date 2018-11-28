<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Model;

/**
 * Class Identifier
 * @package Salamek\MojeOlomouc\Model
 */
class Identifier implements IIdentifier
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
}