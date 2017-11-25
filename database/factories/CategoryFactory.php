<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Category::class, function (Faker $faker) {
    return [
        'task_list_id' => factory(App\Models\TaskList::class)->create()->id,
        'name' => $faker->sentence(2, true),
        'list_element_id' => uniqid()
    ];
});
