<?php

namespace Bot\Telegram;

use Laravel\Lumen\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Telegram;

class TelegramAPI extends Telegram
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Data
     */
    protected $data;

    /**
     * @var Application
     */
    protected $app;

    /**
     * TelegramAPI constructor.
     *
     * @param string $bot_token
     * @param Request|null $request
     * @param Application $app
     */
    public function __construct($bot_token, Application $app = null, Request $request = null)
    {
        $this->app = $app;
        $this->request = $request ?? $app->make('request');

        parent::__construct($bot_token);
    }

    /**
     * @return Data
     */
    public function getData()
    {
        $this->data = $this->request->json()->all();

        return $this->data;
    }

    /**
     * @param string $text
     * @param array $args
     * @param null|string $chat_id
     *
     * @return Message
     */
    public function newMessage($text, array $args = [], $chat_id = null)
    {
        return new Message($this, $text, $args, $chat_id);
    }

    /**
     * @param string $text
     * @param array $args
     * @param null|string $chat_id
     *
     * @return Message
     */
    public function replyToMessage($text, array $args = [], $chat_id = null)
    {
        return $this->newMessage($text, $args, $chat_id)->sendReply($this->message_id);
    }

    /**
     * @param string $text
     * @param array $args
     * @param null|string $chat_id
     *
     * @return Message
     */
    public function replyToMessageHTML($text, array $args = [], $chat_id = null)
    {
        return $this->newMessage($text, $args, $chat_id)->sendReplyHTML($this->message_id);
    }

    /**
     * @return mixed
     */
    public function deleteLastMessage()
    {
        return $this->deleteMessage(["chat_id" => $this->chat_id, "message_id" => $this->message_id]);
    }
}