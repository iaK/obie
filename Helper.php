<?php

use App\User;
use Illuminate\Auth\Middleware\Auth;

public function auth($bot)
{
    $user = $bot->getUser();
    if(!$user) {
        $this->sayNotAuthenticated($bot);
    }

    $id = $user->getId();

    if(!$id) {
        $this->sayNotAuthenticated($bot);
    }

    $user = User::whereFacebookId($id)->first();

    if(!$user) {
        $this->sayNotAuthenticated($bot);
    }

    Auth::login($user);
}
