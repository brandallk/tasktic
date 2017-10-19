<?php

use Faker\Generator as Faker;

$factory->define(App\Models\TaskList::class, function (Faker $faker) {
    return [
        'user_id' => $faker->randomDigitNotNull,
        'name' => $faker->sentence(5, true),
    ];
});
