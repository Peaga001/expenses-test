<?php

namespace App\Utils;

class PriceUtils
{
    public static function formatValueToDisplay(
        int | float | string $value
    ): string
    {
        return 'R$'.number_format($value, 2, ',','.');
    }

    public static function formatValueToSave(
        int | float | string $value
    ): string
    {
        return number_format(floatval($value), 2);
    }
}
