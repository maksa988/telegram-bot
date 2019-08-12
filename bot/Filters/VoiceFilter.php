<?php

namespace Bot\Filters;

class VoiceFilter extends Filter
{
    /**
     * @param null $message
     * @return int
     * @throws \Exception
     */
    public function execute($message = null)
    {
        if(! $this->data->isGroupChat())
            return self::MESSAGE_SKIP;

        if(!$this->data->hasVoice())
            return Filter::MESSAGE_SKIP;

        $this->api->deleteLastMessage();

        return Filter::MESSAGE_STOP;
    }
}