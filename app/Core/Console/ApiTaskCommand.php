<?php

namespace LarAPI\Core\Console;

use Throwable;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

use LarAPI\Common\Support\Formatter;
use LarAPI\Models\Common\ApiTask;
use LarAPI\Repositories\Common\ApiTaskRepository;

abstract class ApiTaskCommand extends Command
{
    /** @var ApiTaskRepository */
    private $apiTaskRepository;
    /** @var ApiTask */
    private $apiTask;
    /** @var Carbon */
    private $initTime;

    /**
     * @return bool - Returns true on success and false on failure
     */
    protected function initializeTask(): bool
    {
        $options = collect($this->options())
            ->except(['help', 'quiet', 'verbose', 'version', 'ansi', 'no-ansi', 'no-interaction', 'env'])
            ->toArray();

        $arguments = $this->arguments();
        $command   = $arguments['command'];
        unset($arguments['command']);

        $this->apiTask = $this->apiTaskRepository()->getByParams([
            ['command', $command],
            ['options', \json_encode($options)],
            ['arguments', \json_encode($arguments)]
        ])->first();

        if (\is_null($this->apiTask)) {
            $this->apiTask = $this->apiTaskRepository()->getModel();

            $this->apiTask->command   = $command;
            $this->apiTask->options   = $options;
            $this->apiTask->arguments = $arguments;
            $this->apiTask->status    = ApiTask::STATUS_RUNNING;
            $this->apiTask->last_run  = Carbon::now();

            $this->apiTask->save();
            $this->initTime = Carbon::now();
            return true;
        }

        if ($this->apiTask->isRunning()) {
            $now     = Carbon::now()->format(Formatter::API_DATETIME_FORMAT);
            $context = \json_encode([
                'arguments' => $arguments,
                'options'   => $options
            ]);
            $message = "[{$now}]: {$command} IS ALREADY RUNNING! ({$context})";

            $this->warn($message);
            Log::warning($message);
            return false;
        }

        $this->apiTask->status     = ApiTask::STATUS_RUNNING;
        $this->apiTask->last_run   = Carbon::now();
        $this->apiTask->last_error = null;

        $this->apiTask->save();
        $this->initTime = Carbon::now();
        return true;
    }

    /**
     * @param Throwable|null $exception
     * @return void
     */
    protected function finishTask(?Throwable $exception = null): void
    {
        $this->apiTask->status        = ApiTask::STATUS_IDLE;
        $this->apiTask->last_run_time = $this->initTime->diffInSeconds();
        if (!\is_null($exception)) {
            $this->apiTask->last_error = $exception->getMessage();
        }

        $this->apiTask->save();
    }

    /**
     * @return ApiTaskRepository
     */
    private function apiTaskRepository(): ApiTaskRepository
    {
        if (\is_null($this->apiTaskRepository)) {
            $this->apiTaskRepository = resolve(ApiTaskRepository::class);
        }

        return $this->apiTaskRepository;
    }
}
