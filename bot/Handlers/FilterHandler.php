<?php

namespace Bot\Handlers;

use Bot\Filters\Filter;
use Bot\Telegram\Data;
use Bot\Telegram\TelegramAPI;
use Illuminate\Config\Repository;
use Laravel\Lumen\Application;

class FilterHandler
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @var TelegramAPI
     */
    protected $api;

    /**
     * @var Repository
     */
    protected $config;

    /**
     * @var Data
     */
    protected $data;

    /**
     * FilterHandler constructor.
     *
     * @param Application $application
     * @param TelegramAPI $api
     * @param Data $data
     */
    public function __construct(Application $application, TelegramAPI $api, Data $data)
    {
        $this->app = $application;
        $this->api = $api;
        $this->data = $data;

        $this->config = $this->app->make('config');
    }

    /**
     * @return array
     */
    public function filter()
    {
        foreach ($this->config->get('filters') as $filter) {
            if($this->filterExists($filter)) {
                $filter = $this->app->make($filter);

                if($filter->execute($this->data->getText()) == Filter::MESSAGE_STOP)
                    return [
                        "status" => Filter::MESSAGE_STOP,
                        "filter" => $filter
                    ];
            }
        }

        return [
            "status" => Filter::MESSAGE_SKIP
        ];
    }

    /**
     * Check filter class file
     *
     * @param $className
     * @return bool
     */
    protected function filterExists($className)
    {
        if(class_exists($className))
            return true;

        return false;
    }
}