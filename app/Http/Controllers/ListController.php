<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Conversations\CreateListConversation;
use App\Conversations\JoinShoppingListConversation;
use App\Conversations\ChangeListPasswordConversation;

class ListController extends Controller
{
    public function index($bot)
    {
        $this->auth($bot);

        $bot->reply(view("showLists", [
            "lists" => $this->user->shoppingLists]
        )->render());
    }

    public function join($bot, $user, $listname)
    {
        $this->auth($bot);

        $bot->startConversation(new JoinShoppingListConversation($user, $listname));
    }


    public function create($bot)
    {
        $this->auth($bot);

        $bot->startConversation(new CreateListConversation($this->user));
    }

    public function updatePassword($bot)
    {
        $this->auth($bot);

        $bot->startConversation(new ChangeListPasswordConversation($this->user));
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
