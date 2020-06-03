<?php

namespace LarAPI\Common\Services;

use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class SlackClient
{
    /** @var string */
    private $botName;
    /** @var string */
    private $botIcon;
    /** @var string */
    private $webhook;
    /** @var string */
    private $defaultChannel;

    /**
     * SlackClient constructor
     *
     * @param string $botName
     * @param string $botIcon
     * @param string $webhook
     * @param string $defaultChannel
     */
    public function __construct(string $botName, string $botIcon, string $webhook, string $defaultChannel)
    {
        $this->botName        = $botName;
        $this->botIcon        = $botIcon;
        $this->webhook        = $webhook;
        $this->defaultChannel = $defaultChannel;
    }

    /**
     * Notify the Slack channel, sending the given message and mentioning the given users
     *
     * @param string $message
     * @param array  $users
     * @param string $target - IF CHANNEL: '#channel' IF USER: '@username'
     * @return void
     */
    public function sendNotification(string $message, array $users = [], string $target = null): void
    {
        try {
            $formattedUsers  = $this->formatUsersToMention($users);
            $finalMessage    = empty($formattedUsers) ? $message : "{$formattedUsers}: {$message}";
            $channelToNotify = empty($target) ? $this->defaultChannel : $target;

            if (App::environment(['local', 'testing'])) {
                $channelToNotify = config('services.slack.channels.local');
            }
            if (App::environment('development')) {
                $channelToNotify = config('services.slack.channels.dev');
            }

            $payloadData = [
                'username'   => $this->botName,
                'icon_emoji' => $this->botIcon,
                'channel'    => $channelToNotify,
                'text'       => $finalMessage
            ];

            $payload = ['payload' => \json_encode($payloadData)];
            $ch      = \curl_init($this->webhook);

            $options = [
                CURLOPT_RETURNTRANSFER => true, // Avoid outputting the return in the console
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_POST           => true,
                CURLOPT_POSTFIELDS     => $payload,
            ];

            \curl_setopt_array($ch, $options);
            if (!\curl_exec($ch)) {
                throw new Exception(\curl_error($ch));
            }
            \curl_close($ch);
        } catch (Exception $exception) {
            $msgTpl = 'Failed to notify Slack Channel for webhook %s: "%s"';
            Log::error(
                \sprintf($msgTpl, $this->webhook, $exception->getMessage())
            );
        }
    }

    /**
     * Formats the given users array to mention on the Slack message
     *
     * @param array $users
     * @return string
     */
    private function formatUsersToMention(array $users): string
    {
        if (empty($users)) {
            return '';
        }

        $formatted = \array_map(function ($user) {
            return "<@{$user}>";
        }, $users);

        return \implode(', ', $formatted);
    }
}
