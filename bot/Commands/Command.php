<?php

namespace Bot\Commands;

use Bot\Telegram\Data;
use Bot\Telegram\TelegramAPI;
use Laravel\Lumen\Application;

abstract class Command
{
    /**
     * @var string
     */
    public static $command;

    /**
     * @var Application
     */
    protected $application;

    /**
     * @var TelegramAPI
     */
    protected $api;

    /**
     * @var Data
     */
    protected $data;

    /**
     * Command constructor.
     * @param Application $application
     * @param TelegramAPI $api
     * @param Data $data
     */
    public function __construct(Application $application, TelegramAPI $api, Data $data)
    {

        $this->application = $application;
        $this->api = $api;
        $this->data = $data;
    }

    /**
     * @param string $args
     * @param string $delimiter
     *
     * @return array
     */
    protected function getParameters($args, $delimiter = ' ')
    {
        return explode($delimiter, $args);
    }

    /**
     * Execute command
     *
     * @param $args
     * @return mixed
     */
    public abstract function execute($args);
}