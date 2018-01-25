<?php
use App\Http\Middleware\AuthMiddleware;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ListController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BotManController;
use App\Http\Controllers\CommandController;
use App\Http\Controllers\FallbackController;

$botman = resolve('botman');

$middleware = new AuthMiddleware;

$botman->group([],function($bot) {
    $bot->hears("anslut till {user} lista {listname}", ListController::class.'@join');
    $bot->hears("lämna lista {listname}", UserController::class."@leave");
    $bot->hears("använd {listname}", UserController::class."@setActiveList");
    $bot->hears("ändra användarnamn", UserController::class."@updateName");
    $bot->hears("visa användarnamn", UserController::class."@showUsername");

    $bot->hears("visa listor", ListController::class."@index");
    $bot->hears("radera lista {listname}", ListController::class."@delete");
    $bot->hears("skapa lista", ListController::class."@create");
    $bot->hears("ändra lösenord", ListController::class."@updatePassword");

    $bot->hears("hjälp", CommandController::class."@index");

    $bot->hears("visa lista", ItemController::class."@index");
    $bot->hears("töm", ItemController::class.'@empty');
    $bot->hears("ta bort (.+)", ItemController::class.'@destroy');
    $bot->hears("(handla|köp|lägg till) (.+)", ItemController::class.'@store');

    $bot->fallback(FallbackController::class."@index");
});
// $botman->hears("handla (.+)", ItemController::class, '@store');
