<?php

namespace LarAPI\Common\Support\DTOs\Traits;

use Carbon\Carbon;

trait HasDateRange
{
    /** @var Carbon|null */
    protected $from = null;
    /** @var Carbon|null */
    protected $to = null;

    /**
     * @param Carbon|null $from
     * @return $this
     */
    public function setFrom(?Carbon $from)
    {
        $this->from = $from;
        return $this;
    }

    /**
     * @return Carbon|null
     */
    public function getFrom(): ?Carbon
    {
        return $this->from;
    }

    /**
     * @param Carbon|null $to
     * @return $this
     */
    public function setTo(?Carbon $to)
    {
        $this->to = $to;
        return $this;
    }

    /**
     * @return Carbon|null
     */
    public function getTo(): ?Carbon
    {
        return $this->to;
    }
}
