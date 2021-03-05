<?php

namespace LarAPI\Modules\Common\Support;

use Illuminate\Support\Carbon;

class Formatter
{
    public const ON_LABEL  = 'ON';
    public const OFF_LABEL = 'OFF';
    public const YES_LABEL = 'YES';
    public const NO_LABEL  = 'NO';
    public const NA_LABEL  = 'N/A';

    public const API_DATE_FORMAT          = 'Y-m-d';
    public const API_DATETIME_FORMAT      = 'Y-m-d H:i:s';
    public const AMERICAN_DATE_FORMAT     = 'm/d/Y';
    public const AMERICAN_DATETIME_FORMAT = 'm/d/Y H:i:s';

    public const DEFAULT_CURRENCY = '$';

    /**
     * Formats a int value
     *
     * @param mixed $value
     * @return string
     */
    public function formatInt($value): string
    {
        return \number_format($value, 0);
    }

    /**
     * Formats a float value
     *
     * @param mixed $value
     * @return string
     */
    public function formatFloat($value): string
    {
        return \number_format($value, 2);
    }

    /**
     * Formats a money value
     *
     * @param mixed  $value
     * @param string $currency
     * @return string
     */
    public function formatMoney($value, string $currency = self::DEFAULT_CURRENCY): string
    {
        return $currency . $this->formatFloat($value);
    }

    /**
     * Formats a boolean value
     *
     * @param bool   $value
     * @param string $trueValue
     * @param string $falseValue
     * @return string
     */
    public function formatBoolean(
        bool $value,
        string $trueValue = self::YES_LABEL,
        string $falseValue = self::NO_LABEL
    ): string {
        return $value ? $trueValue : $falseValue;
    }

    /**
     * @param string      $date
     * @param string      $format
     * @param string|null $timezone
     * @return Carbon|null
     */
    public function getCarbonFromString(string $date, string $format = self::API_DATE_FORMAT, ?string $timezone = null): ?Carbon
    {
        $date = Carbon::createFromFormat($format, $date, $timezone);
        return empty($date) ? null : $date;
    }

    /**
     * @param int         $timestamp
     * @param string|null $timezone
     * @return Carbon
     */
    public function getCarbonFromTimestamp(int $timestamp, ?string $timezone = null): Carbon
    {
        return Carbon::createFromTimestamp($timestamp, $timezone);
    }
}
