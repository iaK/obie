<?php

namespace App\Http\Controllers;

use App\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    protected $user;

    public function __construct($bot)
    {
        $this->user = auth()->user();
    }

    public function index($bot)
    {
        $this->auth($bot);

        if (!$this->user->activeList) {
            $bot->reply("Du har ingen aktiv lista. Skapa/anslut/använd en.");
            return;
        }

        $list = $this->user->activeList;

        $bot->reply(view("showList", ["list" => $list])->render());
    }


    public function store($bot, $items)
    {
        $this->auth($bot);

        if (!$this->user->activeList) {
            $bot->reply("Du har ingen aktiv lista. Skapa/anslut/använd en.");
            return;
        }

        collect(explode(",", $items))->each( function($item) {
            $this->user->activeList->addItem($item);
        });

        $bot->reply("ok");
    }

    public function destroy($bot, $items)
    {
        $this->auth($bot);

        if (!$this->user->activeList) {
            $bot->reply("Du har ingen aktiv lista. Skapa/anslut/använd en.");
            return;
        }

        collect(explode(",", $items))->each( function($item) {
            $this->user->activeList->removeItem($item);
        });

        $bot->reply("ok");
    }

    public function empty($bot)
    {
        $this->auth($bot);

        if (!$this->user->activeList) {
            $bot->reply("Du har ingen aktiv lista. Skapa/anslut/använd en.");
            return;
        }

        $this->user->activeList->clear();

        $bot->reply("ok");
    }



}
