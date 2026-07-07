<?php

namespace App\Enums;

enum TimelineType: string
{
    case Case = 'case';
    case Investigation = 'investigation';
    case Trial = 'trial';
    case Appeal = 'appeal';
    case Character = 'character';
    case Custom = 'custom';

    public function label(): string
    {
        return match ($this) {
            self::Case => 'Case',
            self::Investigation => 'Investigation',
            self::Trial => 'Trial',
            self::Appeal => 'Appeal',
            self::Character => 'Character',
            self::Custom => 'Custom',
        };
    }
}