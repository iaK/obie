<?php

use App\Item;
use App\User;
use App\ShoppingList;
use Faker\Generator as Faker;

$factory->define(Item::class, function (Faker $faker) {
    return [
        "user_id" => function(){
            return factory(User::class)->create()->id;
        },
        "shopping_list_id" => function(){
            return factory(ShoppingList::class)->create()->id;
        },
        "name" => $faker->name,
    ];
});
