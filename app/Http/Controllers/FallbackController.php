<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Conversations\CreateUserConversation;

class FallbackController extends Controller
{
    public function index($bot)
    {
        if(!Auth::check()) {
            $bot->startConversation(new CreateUserConversation);
        } else {
            $bot->reply("Jag känner inte igen det kommandot. skriv \"hjälp\" för att se alla mina kommandon");
        }
    }

}
