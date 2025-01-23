<?php

namespace App\Enums;

enum QuestionType: string
{
    case SHORT_TEXT = 'short_text';
    case LONG_TEXT = 'long_text';
    case RADIO = 'radio';
    case CHECKBOX = 'checkbox';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
