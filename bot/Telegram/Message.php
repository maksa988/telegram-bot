<?php

namespace Bot\Telegram;

class Message
{
    /**
     * @var TelegramAPI
     */
    protected $api;

    /**
     * @var int
     */
    public $chat_id;

    /**
     * @var string
     */
    public $text;

    /**
     * @var array
     */
    public $args = [];

    /**
     * Message constructor.
     *
     * @param TelegramAPI $api
     * @param string $text
     * @param array $args
     * @param null|int $chat_id
     */
    public function __construct(TelegramAPI $api, $text, array $args = [], $chat_id = null)
    {
        $this->api = $api;

        if(is_null($chat_id)) {
            $this->chat_id = $api->ChatID();
        } else {
            $this->chat_id = $chat_id;
        }

        $this->text = $text;
        $this->args = $args;
    }

    /**
     * Send message
     *
     * @return mixed
     */
    public function send()
    {
        $data = array_merge(["chat_id" => $this->chat_id, "text" => $this->text], $this->args);

        dump($data);

        return $this->api->sendMessage($data);
    }

    /**
     * Send message with HTML
     *
     * @return mixed
     */
    public function sendHTML()
    {
        $this->args['parse_mode'] = "HTML";

        return $this->send();
    }

    /**
     * Reply to $message_id
     *
     * @param $message_id
     * @return mixed
     */
    public function sendReply($message_id)
    {
        $this->args['reply_to_message_id'] = $message_id;

        return $this->send();
    }

    /**
     * Reply to $message_id using HTML
     *
     * @param $message_id
     * @return mixed
     */
    public function sendReplyHTML($message_id)
    {
        $this->args['reply_to_message_id'] = $message_id;

        return $this->sendHTML();
    }
}