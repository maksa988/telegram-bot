<?php

namespace Bot\Handlers;

use Bot\Telegram\Data;
use Bot\Telegram\TelegramAPI;
use Illuminate\Contracts\Config\Repository;
use Laravel\Lumen\Application;

class EventHandler
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
     * @var array
     */
    protected $events = [];

    /**
     * CommandHandler constructor.
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

        $this->events = $this->config->get('events');
    }

    /**
     * @param null $name
     */
    public function getEvent($name = null)
    {
        $this->executeEvent($this->events, $name);
    }

    /**
     * @param array $list
     * @param null $name
     */
    protected function executeEvent($list = [], $name = null)
    {
        $message = $this->data->getMessage();

        foreach ($list as $eventName => $eventClass)
        {
            if(! is_null($name))
                $eventName = $name;

            if(is_array($eventClass)) {
                $this->executeEvent($eventClass, $eventName);
            } else {
                if (isset($message[$eventName])) {
                    if ($this->eventExists($eventClass)) {
                        $event = $this->app->make($eventClass);

                        $event->execute($message[$eventName]);
                    }
                }
            }
        }
    }

    /**
     * Check event class file
     *
     * @param $className
     * @return bool
     */
    protected function eventExists($className)
    {
        if(class_exists($className))
            return true;

        return false;
    }
}