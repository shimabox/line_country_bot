<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use LINE\LINEBot;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;

class LINEBotServiceProvider extends ServiceProvider
{
    /**
     * Register LINEBot
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(LINEBot::class, function () {
            $channelToken = env('CHANEL_ACCESS_TOKEN');
            $channelSecret = env('CHANNEL_SECRET');

            return new LINEBot(new CurlHTTPClient($channelToken), [
                'channelSecret' => $channelSecret
            ]);
        });
    }
}
