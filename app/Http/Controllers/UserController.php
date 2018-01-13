<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Conversations\CreateUserConversation;

class UserController extends Controller
{
    public function create($bot)
    {
        $bot->startConversation(new CreateUserConversation);
    }

}
