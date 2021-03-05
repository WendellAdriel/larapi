<?php

namespace LarAPI\Core\Console;

use DateTime;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use LarAPI\Modules\Common\Support\Formatter;
use LarAPI\Models\Common\ApiTask;
use LarAPI\Repositories\Common\ApiTaskRepository;
use Throwable;

abstract class ApiTaskCommand extends Command
{
    private ApiTaskRepository $apiTaskRepository;
    private ?ApiTask $apiTask;
    private Carbon $initTime;

    /**
     * @return void
     */
    abstract public function handleCommand(): void;

    public function handle()
    {
        if (!$this->initializeTask()) {
            return;
        }
        try {
            $this->handleCommand();
            $this->finishTask();
        } catch (Throwable $exception) {
            $this->finishTask($exception);
        }
    }

    /**
     * @return bool - Returns true on success and false on failure
     */
    protected function initializeTask(): bool
    {
        $this->logInfo('PROCESS STARTING...');
        if (App::environment('local')) {
            $this->warn("IGNORING SCHEDULED COMMANDS MANAGER ON LOCAL ENVIRONMENT!");
            return true;
        }

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
        ]);

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
            $context = \json_encode([
                'arguments' => $arguments,
                'options'   => $options
            ]);
            $message = "COMMAND IS ALREADY RUNNING! ({$context})";

            $this->logWarning($message);
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
        if (!\is_null($exception)) {
            $this->logError("Error executing {$this->getName()}: {$exception->getMessage()}");
        }
        $this->logInfo('PROCESS FINISHED!');
        if (App::environment('local')) {
            return;
        }

        $this->apiTask->status        = ApiTask::STATUS_IDLE;
        $this->apiTask->last_run_time = $this->initTime->diffInSeconds();
        if (!\is_null($exception)) {
            $this->apiTask->last_error = $exception->getMessage();
        }

        $this->apiTask->save();
    }

    /**
     * @param string $message
     * @return void
     */
    protected function logInfo(string $message): void
    {
        $this->info($this->formatMessage($message, true));
        Log::info($this->formatMessage($message));
    }

    /**
     * @param string $message
     * @return void
     */
    protected function logWarning(string $message): void
    {
        $this->warn($this->formatMessage($message, true));
        Log::warning($this->formatMessage($message));
    }

    /**
     * @param string $message
     * @return void
     */
    protected function logError(string $message): void
    {
        $this->error($this->formatMessage($message, true));
        Log::error($this->formatMessage($message));
    }

    /**
     * @param string $message
     * @param bool   $includeDateTime
     * @return string
     */
    private function formatMessage(string $message, bool $includeDateTime = false): string
    {
        $separator = $includeDateTime ? ': ' : ' ';
        $suffix    = $includeDateTime
            ? '[' . (new DateTime())->format(Formatter::API_DATETIME_FORMAT) . ']'
            : '';

        return "{$suffix}({$this->getName()}){$separator}{$message}";
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
