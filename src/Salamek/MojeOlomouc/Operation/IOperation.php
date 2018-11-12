<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Operation;

use Salamek\MojeOlomouc\Request;

/**
 * Interface IOperation
 * @package Salamek\MojeOlomouc\Operation
 */
interface IOperation
{
    /**
     * IOperation constructor.
     * @param Request $request
     * @param string|null $hydrator
     */
    public function __construct(Request $request, string $hydrator = null);
}