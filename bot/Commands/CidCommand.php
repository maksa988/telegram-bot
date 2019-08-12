<?php

namespace Bot\Commands;

class CidCommand extends Command
{
    /**
     * @var string
     */
    public static $command = '/cid';

    /**
     * Execute command
     *
     * @param $args
     * @return mixed
     */
    public function execute($args)
    {
        $this->api->deleteLastMessage();

        return $this->api->newMessage("Chat ID: <b>". $this->api->ChatID() ."</b>")->sendHTML();
    }
}