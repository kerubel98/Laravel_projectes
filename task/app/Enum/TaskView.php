<?php

namespace App\Enum;

enum TaskView: string
{
    case PUBLIC = 'public';
    case PRIVATE = 'private';
    case NONE = 'none';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
