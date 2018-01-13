<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Conversations\CreateListConversation;

class ListController extends Controller
{
    public function store($bot)
    {
        //create new list, choose password etc.
    }

    public function create($bot)
    {
        $bot->startConversation(new CreateListConversation);
    }


}
