<?php

namespace App\Enums;

enum ResultTypeEnums: string
{
    case REGULAR = 'regular';
    case WIN_OVERTIME = 'win_overtime';
    case WIN_OVERTIME_SHOTS = 'win_overtime_shots';


    public function label(): string
    {
        return match($this)
        {
            self::REGULAR => '',
            self::WIN_OVERTIME => 'OT',
            self::WIN_OVERTIME_SHOTS => 'OS',
        };
    }
}
