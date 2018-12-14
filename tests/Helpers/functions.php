<?php

function create($class, $attributes = [], $quantity = null)
{
    return factory($class, $quantity)->create($attributes);
}

function make($class, $attributes = [], $quantity = null)
{
    return factory($class, $quantity)->make($attributes);
}
