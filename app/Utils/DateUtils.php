<?php

namespace App\Utils;

use Carbon\Carbon;

class DateUtils
{
    public static function formatDateToDisplay(string | Carbon $date): string
    {
        $format = config('values.date_format');

        if(is_string($date)){
            return Carbon::make($date)->format($format);
        }

        return $date->format($format);
    }

    public static function formatDateToSave(string | Carbon $date): string
    {
        if(is_string($date)){
            return Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d');
        }

        return $date->format('Y-m-d');
    }
}
