<?php

use Faker\Generator as Faker;

$factory->define(App\Models\TaskList::class, function (Faker $faker) {
    return [
        'user_id' => factory(App\Models\User::class)->create()->id,
        'name' => $faker->sentence(5, true),
    ];
});
