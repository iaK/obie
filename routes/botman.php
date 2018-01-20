<?php
use App\Http\Middleware\AuthMiddleware;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ListController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BotManController;

$botman = resolve('botman');

$botman->hears("hej", UserController::class."@create");

$middleware = new AuthMiddleware;

$botman->group([],function($bot) {
    $bot->hears("anslut till {user} lista {list}", ListController::class.'@join');
    $bot->hears("lämna lista {list}", UserController::class."@leave");
    $bot->hears("radera lista {listname}", ListController::class."@delete");
    $bot->hears("använd {list}", UserController::class."@setActiveList");
    $bot->hears("handla (.+)", ItemController::class.'@store');
    $bot->hears("ta bort (.+)", ItemController::class.'@destroy');
    $bot->hears("töm", ItemController::class.'@empty');
    $bot->hears("visa lista", ListController::class."@show");
    $bot->hears("skapa lista", ListController::class."@create");
    // Fixa!
    //$bot->hears("visa listor");

    $bot->hears("hjälp", function($bot) {
        $bot->reply("
        * \"anslut till [användarnamn] lista [lista]\" - Anslut till en annan användares lista.
        * \"skapa lista\" - Skapa en lista.
        * \"lämna lista [lista]\" - Lämna lista.
        * \"radera lista [lista]\" - Radera lista.
        * \"handla [vara]\" - Sätt upp en vara på listan.
        * \"ta bort [vara]\" - Ta bort en vara från listan.
        * \"töm\" - Töm aktiv lista.
        * \"visa lista\" - Visa hela aktiva listan.
        * \"använd [lista]\" - Byt aktiv lista");
    });

    $bot->fallback(function($bot) {
        $bot->reply("Jag känner inte igen det kommandot. skriv \"hjälp\" för att se alla mina kommandon");
    });
});
// $botman->hears("handla (.+)", ItemController::class, '@store');
