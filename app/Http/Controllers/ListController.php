<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Conversations\CreateListConversation;
use App\Conversations\JoinShoppingListConversation;

class ListController extends Controller
{

    public function __construct($bot)
    {
        $this->user = auth()->user();
    }

    public function store($bot)
    {
        //create new list, choose password etc.
    }

    public function show($bot)
    {
        $this->auth($bot);

        if (!$this->user->activeList) {
            $bot->reply("Du har ingen aktiv lista. Skapa/anslut/använd en.");
            return;
        }

        $list = auth()->user()->activeList;

        $bot->reply(view("showList", ["list" => $list])->render());
    }

    public function join($bot, $user, $list)
    {
        $this->auth($bot);

        $bot->startConversation(new JoinShoppingListConversation($user, $list));
    }


    public function create($bot)
    {
        $this->auth($bot);

        $bot->startConversation(new CreateListConversation);
    }

    public function delete($bot, $listname)
    {
        $this->auth($bot);

        $list = auth()
            ->user()
            ->ownedShoppingLists()
            ->whereName($listname)
            ->first();

        if ($list) {
            $list->delete();

            return $bot->reply("Listan borttagen.");
        }

        return $bot->reply("Du har ingen lista som heter $listname");
    }



}
