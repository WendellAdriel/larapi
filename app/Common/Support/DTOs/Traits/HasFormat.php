<?php

namespace LarAPI\Common\Support\DTOs\Traits;

trait HasFormat
{
    /** @var string */
    protected $format = 'json';

    /**
     * @param string $format
     * @return $this
     */
    public function setFormat(string $format)
    {
        $this->format = $format;
        return $this;
    }

    /**
     * @return string
     */
    public function getFormat(): string
    {
        return $this->format;
    }
}
