<?php

namespace Bot\Handlers;

use Bot\Telegram\Data;
use Bot\Telegram\TelegramAPI;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\Str;
use Laravel\Lumen\Application;

class CommandHandler
{
    /**
     * @var string
     */
    protected $message = null;

    /**
     * Command text (argument)
     * @var string|array
     */
    protected $text = null;

    /**
     * Command (ex. /help)
     * @var string
     */
    protected $command = null;

    /**
     * Command entity
     * @var array
     */
    protected $entity = [];

    /**
     * @var bool
     */
    protected $my_command = false;

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
    }

    /**
     * @return bool
     */
    public function isCommandToMe()
    {
        if((Str::contains($this->command, "@" . $this->config->get('bot.name'))) === false
            && (Str::contains($this->command, "@")) !== false)
            $this->my_command = false;
        else
            $this->my_command = true;

        return $this->my_command;
    }

    /**
     * Parse command
     *
     * @return $this
     */
    public function getCommand()
    {
        $this->command = Str::substr($this->message, $this->entity['offset'], $this->entity['length']);

        if(! $this->isCommandToMe())
            return $this;

        $this->command = Str::replaceFirst("@" . $this->config->get('bot.name'), "", $this->command);
        $this->command = Str::replaceFirst("/", "", $this->command);

        return $this;
    }

    /**
     * Parse commands arguments
     *
     * @return $this
     */
    public function getCommandText()
    {
        if($this->entity['offset'] == 0) {
            $this->text = trim(Str::substr($this->message, $this->entity['length']));
        } else {
            $this->text[] = trim(Str::substr($this->message, 0, $this->entity['offset']));
            $this->text[] = trim(Str::substr($this->message, $this->entity['length'] + $this->entity['offset']));
        }

        return $this;
    }

    /**
     * Execute command
     *
     * @param $index
     * @return bool
     */
    public function executeCommand($index)
    {
        $this->entity = $this->data->getEntities($index);
        $this->message = $this->data->getText();

        $this->getCommand()->getCommandText();

        $className = Str::ucfirst(Str::lower($this->command));
        $className = $className . "Command";

        if(!$this->commandExists($className))
            return false;

        $className = "\Bot\Commands\\" . $className;

        $commandClass = $this->app->make($className);

        $commandClass->execute($this->text);
    }

    /**
     * Check command class file
     *
     * @param $className
     * @return bool
     */
    public function commandExists($className)
    {
        if(file_exists(__DIR__ . "/../Commands/" . $className . ".php"))
            return true;

        return false;
    }
}