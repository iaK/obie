<?php

namespace App\Conversations;

use App\User;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Conversations\Conversation;

class ChangeUsernameConversation extends Conversation
{
    public static $askNewUsernameText = "Alright, ange ett användarnamn (Bara bokstäver, siffror samt understräck)";

    public static $confirmUsernameText = "Uppdaterat och klart!";

    public static $usernameAlreadyInUse = "Användarnamnet är upptaget, testa ett annat...";

    public function askNewUsername($question)
    {
        $this->ask($question, function(Answer $answer) {

            $username = $answer->getText();

            if (User::whereUsername($username)->first()) {
                $this->askNewUsername($this::$usernameAlreadyInUse);
            }

            $user = auth()->user();
            $user->username = $username;
            $user->save();

            $this->say($this::$confirmUsernameText);

        });
    }

    /**
     * Start the conversation.
     *
     * @return mixed
     */
    public function run()
    {
        $this->askNewUsername($this::$askNewUsernameText);
    }
}
