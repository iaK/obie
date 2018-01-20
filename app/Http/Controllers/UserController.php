<?php

namespace App\Http\Controllers;

use App\ShoppingList;
use Illuminate\Http\Request;
use App\Conversations\CreateUserConversation;

class UserController extends Controller
{
    public function create($bot)
    {
        $bot->startConversation(new CreateUserConversation);
    }

    public function setActiveList($bot, $list)
    {
        $this->auth($bot);

        $list = auth()->user()->shoppingLists()->whereName($list)->first();

        if ($list) {
            auth()->user()->setActiveList($list);

            return $bot->reply("{$list->name} är nu satt som aktiv!");
        }

        $bot->reply("Ingen lista hittad..");
    }
    public function leave($bot, $list)
    {
        $this->auth($bot);

        if ($list = ShoppingList::whereName($list)->first()) {
            auth()->user()->leaveList($list);
        }

        $bot->reply("ok!");
    }



}
