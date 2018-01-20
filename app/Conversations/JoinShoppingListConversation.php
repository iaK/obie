<?php

namespace App\Conversations;

use App\User;
use App\ShoppingList;
use App\Events\ListJoined;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Hash;
use App\Conversations\BaseConversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use BotMan\BotMan\Messages\Conversations\Conversation;

class JoinShoppingListConversation extends BaseConversation
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

            event(new ListJoined($this->user->fresh()->shoppingLists, $this->bot));

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

        $this->list = $this->joinedUser->shoppingLists()->whereName($this->list)->first();

        if(! $this->list) {
            $this->say($this::$listUnknownText);
            return false;
        }

        $this->user = auth()->user();
        $this->askPassword($this::$askPasswordText);
    }

    // /**
    //  * First question
    //  */
    // public function askReason()
    // {
    //     $question = Question::create("Huh - you woke me up. What do you need?")
    //         ->fallback('Unable to ask question')
    //         ->callbackId('ask_reason')
    //         ->addButtons([
    //             Button::create('Tell a joke')->value('joke'),
    //             Button::create('Give me a fancy quote')->value('quote'),
    //         ]);

    //     return $this->ask($question, function (Answer $answer) {
    //         if ($answer->isInteractiveMessageReply()) {
    //             if ($answer->getValue() === 'joke') {
    //                 $joke = json_decode(file_get_contents('http://api.icndb.com/jokes/random'));
    //                 $this->say($joke->value->joke);
    //             } else {
    //                 $this->say(Inspiring::quote());
    //             }
    //         }
    //     });
    // }
}
