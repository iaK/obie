<?php

use App\User;
use App\ShoppingList;
use Faker\Generator as Faker;

$factory->define(ShoppingList::class, function (Faker $faker) {
    static $password;

    return [
        "name" => $faker->word,
        'password' => $password ?: $password = bcrypt('secret'),
    ];
});
