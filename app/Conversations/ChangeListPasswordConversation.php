<?php

namespace App\Conversations;

use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Conversations\Conversation;

class ChangeListPasswordConversation extends Conversation
{
    public static $askNewPasswordText = "Alright, ange ett nytt lösenord";

    public static $confirmPasswordText = "Uppdaterat och klart!";

    public static $notYourListText = "Du äger inte den aktiva listan, då kan du inte ändra lösenord";

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function askNewPassword()
    {
        $this->ask($this::$askNewPasswordText, function(Answer $answer) {

            $list = $this->user->activeList;
            $list->password = bcrypt($answer->getText());
            $list->save();

            $this->say($this::$confirmPasswordText);

        });
    }

    /**
     * Start the conversation.
     *
     * @return mixed
     */
    public function run()
    {
        if (!$this->user->ownsActiveList()) {
            $this->say($this::$notYourListText);
            return;
        }

        $this->askNewPassword();
    }
}
