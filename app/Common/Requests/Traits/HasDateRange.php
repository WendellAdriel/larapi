<?php

namespace LarAPI\Common\Requests\Traits;

use Carbon\Carbon;

trait HasDateRange
{
    /**
     * @return Carbon|null
     */
    public function from(): ?Carbon
    {
        $from = $this->input('from');
        if (!empty($from)) {
            return Carbon::createFromFormat('m/d/Y', $from)->startOfDay();
        }

        $timeframe = $this->input('timeframe');
        if (!empty($timeframe)) {
            return $this->getFromTimeframe($timeframe);
        }

        return null;
    }

    /**
     * @return Carbon|null
     */
    public function to(): ? Carbon
    {
        $to = $this->input('to');
        if (!empty($to)) {
            return Carbon::createFromFormat('m/d/Y', $to)->endOfDay();
        }

        $timeframe = $this->input('timeframe');
        if (!empty($timeframe)) {
            return Carbon::today()->endOfDay();
        }

        return null;
    }

    /**
     * @param $timeframe
     * @return Carbon
     */
    protected function getFromTimeframe(string $timeframe): Carbon
    {
        $time = new Carbon();
        if (preg_match('/^(\d+)(h|d|m|w|y)$/i', $timeframe, $match)) {
            $n = $match[1];
            $t = $match[2];

            switch (strtolower($t)) {
                case 'h':
                    $time->subHours($n);
                    break;

                case 'd':
                    $time->subDays($n)->startOfDay();
                    break;

                case 'm':
                    $time->subMonth($n)->startOfDay();
                    break;

                case 'w':
                    $time->subWeeks($n)->startOfDay();
                    break;

                case 'y':
                    $time->subYear($n)->startOfDay();
                    break;
            }

            return $time;
        }

        return $time->subDays(2)->startOfDay();
    }
}
