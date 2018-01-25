<?php

namespace App\Http\Controllers;

use App\ShoppingList;
use Illuminate\Http\Request;
use App\Conversations\CreateUserConversation;
use App\Conversations\ChangeUsernameConversation;

class UserController extends Controller
{
    public function create($bot)
    {
        $bot->startConversation(new CreateUserConversation);
    }

    public function setActiveList($bot, $listname)
    {
        $this->auth($bot);

        $list = $this->user->shoppingLists()->whereName($listname)->first();

        if ($list) {
            $this->user->setActiveList($list);

            return $bot->reply("{$list->name} är nu satt som aktiv!");
        }

        $bot->reply("Ingen lista hittad..");
    }

    public function leave($bot, $listname)
    {
        $this->auth($bot);

        if ($list = ShoppingList::whereName($listname)->first()) {
            $this->user->leaveList($list);
        }

        $bot->reply("ok!");
    }

    public function updateName($bot)
    {
        $this->auth($bot);

        $bot->startConversation(new ChangeUsernameConversation($this->user));
    }

    public function showUsername($bot)
    {
        $this->auth($bot);

        $bot->reply("Ditt användarnamn: {$this->user->username}");
    }





}
