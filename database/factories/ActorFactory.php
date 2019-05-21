<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Actor;
use Faker\Generator as Faker;

$factory->define(Actor::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'birthday' => $faker->date($format='Y-m-d', $max='1990-01-01'),
        'country' => $faker->country,
    ];
});
