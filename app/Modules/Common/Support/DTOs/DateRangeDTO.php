<?php

namespace LarAPI\Modules\Common\Support\DTOs;

use Illuminate\Support\Carbon;
use LarAPI\Modules\Common\Support\Formatter;

class DateRangeDTO extends CommonTableDTO
{
    public const START_DATE = 'from';
    public const END_DATE   = 'to';

    private ?Carbon $from;
    private ?Carbon $to;

    /**
     * @return Carbon|null
     */
    public function getFrom(): ?Carbon
    {
        return $this->from;
    }

    /**
     * @param string $format
     * @return string|null
     */
    public function getStringFrom(string $format = Formatter::API_DATE_FORMAT): ?string
    {
        return \is_null($this->from) ? null : $this->from->format($format);
    }

    /**
     * @param Carbon|null $from
     * @return DateRangeDTO
     */
    public function setFrom(?Carbon $from): DateRangeDTO
    {
        $this->from = $from;
        return $this;
    }

    /**
     * @return Carbon|null
     */
    public function getTo(): ?Carbon
    {
        return $this->to;
    }

    /**
     * @param string $format
     * @return string|null
     */
    public function getStringTo(string $format = Formatter::API_DATE_FORMAT): ?string
    {
        return \is_null($this->to) ? null : $this->to->format($format);
    }

    /**
     * @param Carbon|null $to
     * @return DateRangeDTO
     */
    public function setTo(?Carbon $to): DateRangeDTO
    {
        $this->to = $to;
        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return \array_merge(parent::toArray(), [
            self::START_DATE => $this->getStringFrom(),
            self::END_DATE   => $this->getStringTo()
        ]);
    }
}
