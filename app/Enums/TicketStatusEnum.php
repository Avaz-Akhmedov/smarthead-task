<?php

namespace App\Enums;

enum TicketStatusEnum: string
{
    case NEW = 'new';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'done';

    public static function values (): array
    {
        return array_column(self::cases(),'value');
    }
}
