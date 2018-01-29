<?php

namespace App\Conversations;

use App\User;
use App\Jobs\Reply;
use App\ShoppingList;
use App\Events\ListJoined;
use Illuminate\Foundation\Inspiring;
use App\Conversations\BaseConversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;

class CreateListConversation extends BaseConversation
{
    protected $username;

    protected $email;

    protected $list;

    protected $user;

    protected $listname;

    public static $confirmListnameText = "\"%s\", vilket fantastiskt namn!";

    public static $askListnameText = "Okej, %s - du vill alltså skapa en ny lista. Vad ska listan heta?";


    public static $askPasswordText = "För att kunna bjuda in andra till din lista behöver du ett lösenord. Svara med ett superhemligt lösenord (jag kommer inte säga det till någon, jag lovar!)";

    public static $confirmPasswordText = "Din lista är nu skapat!";

    public static $instructionText = "För att lägga till saker på listan skriv \n\"handla [namn]\" så lägger jag till det. \nFör att ta bort något, skriv \"ta bort [namn]\". \nFör att se hela din lista, skriv \"visa lista\". \nOch slutligen - för att tömma listan, skriv \"töm\". Svårare än så är det inte :)";

    public static $shareInstructionText = "Om du vill dela din lista till andra, be dom skicka ett meddelande med denna struktur \n \"Anslut till %s lista %s\"\n Dom kommer få skriva in det lösenord du valt, sedan kan dom också se listan, samt lägga till och ta bort saker. \nSå sjukt smidigt!";

    public function __construct($user)
    {
        $this->user = $user;
    }


    public function askListname()
    {
        $this->ask(sprintf($this::$askListnameText, $this->user->username), function(Answer $answer) {
            $this->listname = $answer->getText();

            $this->list = new App\ShoppingList;
            $this->list->name = $this->listname;
            $this->user->createList($this->list);

            Reply::dispatch($this->bot,sprintf($this::$confirmListnameText, $this->listname));
            $this->askPassword();
        });
    }

    public function askPassword()
    {
        $this->ask($this::$askPasswordText, function(Answer $answer) {

            Reply::dispatch($this->bot, $this::$confirmPasswordText);
            Reply::dispatch($this->bot, $this::$instructionText);
            Reply::dispatch($this->bot, sprintf($this::$shareInstructionText, $this->user->username, $this->listname));

            $this->list->password = bcrypt($answer->getText());
            $this->list->save();


            event(new ListJoined($this->user->fresh()->shoppingLists, $this->bot));

            return;
        });
    }


    public function run()
    {
        // This will be called immediately
        $this->askListname();
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
