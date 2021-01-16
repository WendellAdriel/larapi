<?php

namespace LarAPI\Core\Providers;

use Illuminate\Support\ServiceProvider;
use LarAPI\Modules\Common\Services\SlackClient;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerSlackClient();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register Slack Client
     */
    private function registerSlackClient()
    {
        $webhook = config('services.slack.webhook');
        if (!\is_null($webhook)) {
            $this->app->bind(SlackClient::class, function () use ($webhook) {
                return new SlackClient(
                    config('services.slack.bot.name'),
                    config('services.slack.bot.icon'),
                    $webhook,
                    config('services.slack.channel')
                );
            });
        }
    }
}
