<?php
/**
 * Created by PhpStorm.
 * User: sadam
 * Date: 11/8/18
 * Time: 12:09 AM
 */

namespace Salamek\MojeOlomouc\Operation;


use Salamek\MojeOlomouc\Request;

interface IOperation
{
    public function __construct(Request $request);
}