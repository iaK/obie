<?php

namespace App\Http\Controllers;

use App\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = auth()->user();
    }

    public function store($bot, $item)
    {
        auth()->user()->getFirstList()->addItem($item);

        $bot->reply("ok");
    }

    public function destroy($bot, $item)
    {
        auth()->user()->getFirstList()->removeItem($item);

        $bot->reply("ok");
    }

    public function empty($bot)
    {
        auth()->user()->getFirstList()->clear();
    }



}
