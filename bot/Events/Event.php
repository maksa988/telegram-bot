<?php

namespace Bot\Events;

use Bot\Telegram\Data;
use Bot\Telegram\TelegramAPI;
use Illuminate\Queue\SerializesModels;
use Laravel\Lumen\Application;

abstract class Event
{
    use SerializesModels;

    /**
     * @var Application
     */
    protected $app;

    /**
     * @var TelegramAPI
     */
    protected $api;

    /**
     * @var Data
     */
    protected $data;

    /**
     * Filter constructor.
     *
     * @param Application $app
     * @param TelegramAPI $api
     * @param Data $data
     */
    public function __construct(Application $app, TelegramAPI $api, Data $data)
    {
        $this->app = $app;
        $this->api = $api;
        $this->data = $data;
    }

    /**
     * Execute event
     *
     * @param $args
     * @return mixed
     */
    public abstract function execute($args = []);
}
