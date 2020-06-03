<?php

namespace LarAPI\Common\Support;

use Illuminate\Support\Carbon;

class Formatter
{
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
     * @param string $date
     * @param string $timezone
     * @param string $format
     * @return Carbon
     */
    public function getCarbonFromString(string $date, string $timezone = null, string $format = self::API_DATE_FORMAT): Carbon
    {
        return Carbon::createFromFormat($format, $date, $timezone);
    }

    /**
     * @param int    $timestamp
     * @param string $timezone
     * @return Carbon
     */
    public function getCarbonFromTimestamp(int $timestamp, string $timezone = null): Carbon
    {
        return Carbon::createFromTimestamp($timestamp, $timezone);
    }
}
