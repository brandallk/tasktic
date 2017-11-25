<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Task::class, function (Faker $faker) {
    return [
        'subcategory_id' => 1,
        'name' => $faker->sentence(5, true),
        'list_element_id' => uniqid(),
        'status' => 'incomplete',
        'deadline' => null,
        'display_position' => 1
    ];
});
