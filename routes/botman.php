<?php
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ListController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BotManController;

$botman = resolve('botman');

$botman->hears("handla {item}", ItemController::class.'@store');
$botman->hears("ta bort {item}", ItemController::class.'@destroy');
$botman->hears("töm", ItemController::class.'@empty');
$botman->hears("hej", UserController::class."@create");
$botman->hears("skapa lista", ListController::class."@create");
// $botman->hears("handla (.+)", ItemController::class, '@store');
