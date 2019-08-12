<?php

namespace Bot\Providers;

use Bot\Telegram\Data;
use Bot\Telegram\TelegramAPI;
use Illuminate\Support\ServiceProvider;

class TelegramServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function register()
    {
        $this->app->configure('bot');
        $this->app->configure('events');
        $this->app->configure('filters');

        $this->app->singleton(TelegramAPI::class);
        $this->app->singleton(Data::class);

        $this->app->alias(TelegramAPI::class, 'telegram');
        $this->app->alias(Data::class, 'telegramData');

        $this->app->when(TelegramAPI::class)
            ->needs('$bot_token')
            ->give(config('bot.token'));

        $this->app->when(Data::class)
            ->needs('$data')
            ->give($this->app->make('telegram')->getData());
    }

    /**
     * Boot telegram service for the application.
     *
     * @return void
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function boot()
    {
        //
    }
}
