<?php

namespace App\Conversations;

use App\User;
use App\List;
use App\Events\ListJoined;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Hash;
use App\Conversations\BaseConversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use BotMan\BotMan\Messages\Conversations\Conversation;

class JoinListConversation extends BaseConversation
{
    protected $username;

    protected $email;

    protected $list;

    protected $user;

    protected $listname;

    protected $joinedUser;

    public static $askPasswordText = "Ange lösenord";

    public static $wrongPasswordText = "Lösenordet stämmer inte. testa igen..";

    public static $usernameUnknownText = "Ingen sådan användare finns. Kolla om du fått rätt namn och testa igen";

    public static $listUnknownText = "Användaren har ingen sådan lista. Testa ett annat namn..";

    public static $confirmListJoinedText = "Jippi! du har nu anslutit till listan";

    public function __construct($user, $list)
    {
        $this->joinedUser = $user;
        $this->list = $list;
    }


    public function askPassword($text)
    {
        $this->ask($text, function(Answer $answer) {
            $password = $answer->getText();

            if (!Hash::check($password, $this->list->password)) {
                return $this->askPassword($this::$wrongPasswordText);
            }

            $this->user->joinList($this->list);

            event(new ListJoined($this->user->fresh()->lists, $this->bot));

            $this->say($this::$confirmListJoinedText);
        });
    }


    public function run()
    {
        // This will be called immediately
        $this->joinedUser = User::whereUsername($this->joinedUser)->first();

        if(! $this->joinedUser) {
            $this->say($this::$usernameUnknownText);
            return false;
        }

        $this->list = $this->joinedUser->lists()->whereName($this->list)->first();

        if(! $this->list) {
            $this->say($this::$listUnknownText);
            return false;
        }

        $this->user = auth()->user();
        $this->askPassword($this::$askPasswordText);
    }

}
