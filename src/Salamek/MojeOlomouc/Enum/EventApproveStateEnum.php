<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Exception;

/**
 * Class EventApproveStateEnum
 * @package Salamek\MojeOlomouc\Exception
 */
class EventApproveStateEnum
{
    const APPROVED = 0;
    const WAITING_FOR_ADD = 1;
    const WAITING_FOR_UPDATE = 2;
    const WAITING_FOR_DELETE = 3;
}