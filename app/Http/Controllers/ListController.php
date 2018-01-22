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

    public function index($bot)
    {
        $this->auth($bot);

        $bot->reply(view("showLists", ["lists" => $this->user->shoppingLists])->render());
    }


    public function store($bot)
    {
        //create new list, choose password etc.
    }

    public function show($bot)
    {
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
