<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Conversations\CreateUserConversation;

class CommandController extends Controller
{
    public function index($bot)
    {
         $bot->reply(view("showCommands")->render());
    }

}
