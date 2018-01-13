<?php

namespace App\Http\Conversations;

use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Conversations\Conversation;

class CreateUserConversation extends Conversation
{
    protected $user;
    protected $username;


    public static $askUsernameText = "Nämen! Dig känner jag inte igen.. Skriv något coolt användarnamn så jag vet vad jhag kan kalla dig (Obs! Bara bokstäver, siffror samt understräck tack, jag är allergisk mot annat skit)";

    public static $confirmUsernameText = "%s - döhäftigt namn!";

    public static $instructionText = "Nu när du har ett konto, då kan du skapa en lista. Detta gör du genom att skriva \"skapa lista\"";

    public function askUsername()
    {
        $this->ask($this::$askUsernameText, function(Answer $answer) {
            $this->username = $answer->getText();

            $this->user = new App\User;
            $this->user->username = $this->username;
            $this->user->save();

            $this->say(
                sprintf(
                    $this::$confirmUsernameText,
                    $this->username
                )
            );

            $this->say($this::$instructionText);
        });
    }

    /**
     * Start the conversation.
     *
     * @return mixed
     */
    public function run()
    {
        $this->askUsername();
    }
}
