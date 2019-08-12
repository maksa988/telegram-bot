<?php

namespace Bot\Telegram;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Carbon;

class Data implements Arrayable
{
    /**
     * Update data
     *
     * @var array
     */
    protected $data = [];

    /**
     * Message text
     *
     * @var string
     */
    protected $text = null;

    /**
     * Chat ID
     * @var int
     */
    protected $chat_id = null;

    /**
     * Chat data
     *
     * @var array
     */
    protected $chat = [];

    /**
     * Unique ID
     *
     * @var null|int
     */
    protected $update_id = null;

    /**
     * Message data
     *
     * @var array
     */
    protected $message = [];

    /**
     * Message ID
     *
     * @var int
     */
    protected $message_id = null;

    /**
     * From data
     * @var array
     */
    protected $from = [];

    /**
     * Message entities
     *
     * @var array
     */
    protected $entities = [];

    /**
     * Update date
     *
     * @var Carbon
     */
    protected $date;

    /**
     * Data constructor.
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = $data;

        $this->update_id = $this->data['update_id'];

        if(isset($this->data['edited_message']))
            $this->message = $this->data['edited_message'];
        else
            $this->message = $this->data['message'];

        $this->message_id = $this->message['message_id'];

        $this->chat = $this->message['chat'];
        $this->chat_id = $this->chat['id'];

        if(isset($this->message['text']))
            $this->text = $this->message['text'];

        $this->from = $this->message['from'];

        if(isset($this->message['entities']))
            $this->entities = $this->message['entities'];

        $this->date = Carbon::createFromTimestamp($this->message['date']);
    }

    /**
     * @return bool
     */
    public function isGroupChat()
    {
        if(isset($this->chat['type']) && $this->chat['type'] == "private")
            return false;

        return true;
    }

    /**
     * @return string|null
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @return array
     */
    public function getCurrentChat(): array
    {
        return $this->chat;
    }

    /**
     * @return int|null
     */
    public function getUpdateId(): ?int
    {
        return $this->update_id;
    }

    /**
     * @return array
     */
    public function getMessage(): array
    {
        return $this->message;
    }

    /**
     * @return int
     */
    public function getMessageId(): int
    {
        return $this->message_id;
    }

    /**
     * @param string|null $key
     * @param mixed $default
     *
     * @return array
     */
    public function getFrom($key = null, $default = null)
    {
        return data_get($this->from, $key, $default);
    }

    /**
     * @param int $index
     * @return array
     */
    public function getEntities($index = -1): array
    {
        if($index != -1)
            return $this->entities[$index];

        return $this->entities;
    }

    /**
     * @return Carbon
     */
    public function getDate(): Carbon
    {
        return $this->date;
    }

    /**
     * @return bool
     */
    public function hasVoice()
    {
        return isset($this->message['voice']);
    }

    /**
     * @return bool
     */
    public function hasVideoNote()
    {
        return isset($this->message['video_note']);
    }

    /**
     * @return bool|int
     */
    public function hasCommand()
    {
        if(empty($this->getEntities()))
            return false;

        foreach ($this->getEntities() as $index => $entity)
        {
            if($entity['type'] == "bot_command")
                return $index;
        }

        return false;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->data;
    }
}