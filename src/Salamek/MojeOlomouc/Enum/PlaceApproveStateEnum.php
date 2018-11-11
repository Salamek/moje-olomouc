<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Enum;

/**
 * Class PlaceApproveStateEnum
 * @package Salamek\MojeOlomouc\Exception
 */
class PlaceApproveStateEnum
{
    const APPROVED = 0;
    const WAITING_FOR_ADD = 1;
    const WAITING_FOR_UPDATE = 2;
    const WAITING_FOR_DELETE = 3;
}