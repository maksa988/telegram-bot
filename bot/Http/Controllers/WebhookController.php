<?php

namespace Bot\Http\Controllers;

use Bot\Filters\Filter;
use Bot\Handlers\CommandHandler;
use Bot\Handlers\EventHandler;
use Bot\Handlers\FilterHandler;
use Bot\Telegram\Data;
use Bot\Telegram\TelegramAPI;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    /**
     * @var TelegramAPI
     */
    protected $api;

    /**
     * @var Data
     */
    protected $data;

    /**
     * @param TelegramAPI $api
     * @param Data $data
     */
    public function __invoke(TelegramAPI $api, Data $data)
    {
        $this->api = $api;
        $this->data = $data;

        if(config('bot.log_updates')) {
            Log::info($data);
        }

        if($this->handleFilter())
            return;

        $this->handleEvent();

        if($this->handleCommand() !== false)
            return;
    }

    /**
     * @return bool
     */
    protected function handleCommand()
    {
        if(($index = $this->data->hasCommand()) === false)
            return false;

        $handler = app(CommandHandler::class);

        $handler->executeCommand($index);
    }

    /**
     * @return bool
     */
    protected function handleFilter()
    {
        $filter = app(FilterHandler::class);

        $result = $filter->filter();

        if($result['status'] == Filter::MESSAGE_STOP)
            return true;

        return false;
    }

    /**
     * @return void
     */
    protected function handleEvent()
    {
        $event = app(EventHandler::class);

        $event->getEvent();
    }
}