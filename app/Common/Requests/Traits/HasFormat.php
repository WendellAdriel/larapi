<?php

namespace LarAPI\Common\Requests\Traits;

trait HasFormat
{
    /**
     * @param string $default
     * @return string
     */
    public function format($default = 'json'): string
    {
        $format = $this->route()->parameter('format', $default);
        return ltrim($format, '.');
    }
}
