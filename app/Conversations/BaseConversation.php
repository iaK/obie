<?php

namespace App\Conversations;

use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use BotMan\BotMan\Messages\Conversations\Conversation;

abstract class BaseConversation extends Conversation
{
    public function stopsConversation(IncomingMessage $message)
    {
        if (strtolower($message->getText()) == 'stopp') {
            return true;
        }

        return false;
    }
}
