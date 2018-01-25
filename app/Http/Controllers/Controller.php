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

        $user = $this->getUser($bot);

        if (!$user) {
            return $this->sayNotAuthenticated($bot);
        }

        Auth::login($user);

        $this->user = $user;
    }

    public function getUser($bot)
    {
        $user = $bot->getUser();

        if(!$user) {
            return false;
        }

        $id = $user->getId();

        if(!$id) {
            return false;
        }

        $user = User::whereFacebookId($id)->first();

        if (!$user) {
            $user = User::find($id);
        }

        if(!$user) {
            return false;
        }

        return $user;
    }



    public function sayNotAuthenticated($bot)
    {
        $bot->reply("Du verkar inte vara registrerad hos oss. skriv \"hej\" för att registrera dig.");
        throw new \Exception("Unauthorized");
    }
}
