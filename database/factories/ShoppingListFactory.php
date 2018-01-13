<?php

use App\User;
use App\ShoppingList;
use Faker\Generator as Faker;

$factory->define(ShoppingList::class, function (Faker $faker) {
    return [
        "name" => $faker->name,
        "user_id" => function() {
            return factory(User::class)->create()->id;
        },
    ];
});
