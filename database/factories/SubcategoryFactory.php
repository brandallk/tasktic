<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Subcategory::class, function (Faker $faker) {
    return [
        'category_id' => factory(App\Models\Category::class)->create()->id,
        'name' => $faker->sentence(2, true),
        'list_element_id' => uniqid()
    ];
});
