<?php

use Faker\Generator as Faker;

$factory->define(App\Team::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'owner_id' => 1
    ];
});
