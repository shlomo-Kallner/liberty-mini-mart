<?php

use Faker\Generator as Faker;

$factory->define(
    App\Image::class, function (Faker $faker) {
        return [
            'name' => $faker->imageUrl(),
            'path' => '',
            'alt' => $faker->text(20),
            'caption' => $faker->text(80),
        ];
    }
);
