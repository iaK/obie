<?php

function create($model, $args = [], $number = 1)
{
    $models = factory($model, $number)->create($args);

    return oneOrCollection($models);
}

function make($model, $args = [], $number = 1)
{
    $models = factory($model, $number)->make($args);

    return oneOrCollection($models);
}

function oneOrCollection($models)
{
    return $models->count() == "1" ? $models->first() : $models;
}
