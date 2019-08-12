<?php

namespace Bot\Filters;

use Bot\Telegram\Data;
use Bot\Telegram\TelegramAPI;
use Laravel\Lumen\Application;

abstract class Filter
{
    /**
     * Skip message
     *
     * @var integer
     */
    public const MESSAGE_SKIP = 0;

    /**
     * Stop message
     *
     * @var integer
     */
    public const MESSAGE_STOP = 1;

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

    public abstract function execute($message = null);
}