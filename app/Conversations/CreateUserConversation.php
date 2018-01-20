<?php

namespace App\Conversations;

use App\User;
use Illuminate\Support\Facades\App;
use App\Conversations\BaseConversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Conversations\Conversation;

class CreateUserConversation extends BaseConversation
{
    protected $user;
    protected $username;


    public static $askUsernameText = "Nämen! Dig känner jag inte igen.. Skriv något coolt användarnamn så jag vet vad jhag kan kalla dig (Obs! Bara bokstäver, siffror samt understräck tack, jag är allergisk mot annat skit)";

    public static $confirmUsernameText = "%s - döhäftigt namn!";

    public static $instructionText = "Nu när du har ett konto, då kan du skapa en lista. Detta gör du genom att skriva \"skapa lista\"";

    public static $usernameIsTakenText = "Hmm.. det verkar som om \"%s\" är upptaget.. Testa ett nytt användarnamn!";

    public function askUsername($question)
    {
        $this->ask($question, function(Answer $answer) {
            $this->username = $answer->getText();

            if ($this->usernameExists()) {
                return $this->askUsername(
                    sprintf($this::$usernameIsTakenText, $this->username)
                );
            }

            $this->saveUser();
        });
    }

    public function saveUser()
    {
        $this->user = new \App\User;
        $this->user->username = $this->username;
        $this->user->facebook_id = $this->getFbId();
        $this->user->save();

        $this->say(
            sprintf(
                $this::$confirmUsernameText,
                $this->username
            )
        );

        $this->say($this::$instructionText);
    }

    public function usernameExists()
    {
        return User::whereUsername($this->username)->exists();
    }

    public function getFbId()
    {
        if($user = $this->bot->getUser()) {
            return $user->getId();
        }
    }



    /**
     * Start the conversation.
     *
     * @return mixed
     */
    public function run()
    {
        if(!auth()->check()) {
            $this->askUsername($this::$askUsernameText);
        } else {
            $this->say("hejhej..");
        }
    }
}
