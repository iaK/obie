<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $user;

    public function __construct()
    {
        $this->user = auth()->user();
    }

    public function auth($bot)
    {
        if(Auth::check()) {
            return;
        }

        if (! $user = $this->getUser($bot)) {
            return $this->sayNotAuthenticated($bot);
        }

        Auth::login($user);

        $this->user = $user;
    }

    public function getUser($bot)
    {
        $user = $bot->getUser();

        return User::getById($user->getId());
    }



    public function sayNotAuthenticated($bot)
    {
        $bot->reply("Du verkar inte vara registrerad hos oss. skriv \"hej\" för att registrera dig.");
        throw new \Exception("Unauthorized");
    }
}
