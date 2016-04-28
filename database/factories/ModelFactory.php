<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name'  => $faker->lastName,
        'gender'     => 'm',
    ];
});

$factory->define(App\Course::class, function (Faker\Generator $faker) {
    return [
        'begin'           => $faker->numberBetween(1409260244, 1409370244),
        'end'             => $faker->numberBetween(1409370245, 1509370244),
        'title'           => $faker->sentence,
        'candidate_limit' => $faker->numberBetween(2, 10),
    ];
});

