<?php

namespace App\Enums;

enum AwardTypeEnums: string
{
    case GUESSED_WINNER = 'guessed_winner';
    case GUESSED_CORRECT_SCORE = 'guessed_correct_score';


    public function points(): int
    {
        return match($this)
        {
            self::GUESSED_WINNER => 1,
            self::GUESSED_CORRECT_SCORE => 1,
        };
    }
}
