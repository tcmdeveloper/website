<?php

namespace App\Support;

use Carbon\Carbon;

class DateTimeCombiner
{
    public static function combine(?string $date, ?string $time): ?Carbon
    {
        if (blank($date)) {
            return null;
        }

        return Carbon::parse($date.' '.($time ?: '00:00'));
    }
}