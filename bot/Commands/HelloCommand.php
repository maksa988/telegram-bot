<?php

namespace Bot\Commands;

use Bot\Telegram\Message;

class HelloCommand extends Command
{
    /**
     * @var string
     */
    public static $command = '/hello';

    /**
     * @param $args
     * @return mixed|void
     */
    public function execute($args)
    {
        $message = new Message($this->api,"Hello world");

        $message->send();
    }
}