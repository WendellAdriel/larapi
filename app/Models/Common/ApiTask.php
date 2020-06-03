<?php

namespace LarAPI\Models\Common;

use Illuminate\Database\Eloquent\Model;

class ApiTask extends Model
{
    public const STATUS_IDLE    = 'idle';
    public const STATUS_RUNNING = 'running';

    public $timestamps  = false;
    protected $fillable = ['command', 'options', 'arguments', 'status', 'last_run', 'last_error', 'last_run_time'];
    protected $dates    = ['last_run'];
    protected $casts    = [
        'options'   => 'array',
        'arguments' => 'array',
    ];

    /**
     * @return bool
     */
    public function isIdle(): bool
    {
        return $this->status === self::STATUS_IDLE;
    }

    /**
     * @return bool
     */
    public function isRunning(): bool
    {
        return $this->status === self::STATUS_RUNNING;
    }
}
