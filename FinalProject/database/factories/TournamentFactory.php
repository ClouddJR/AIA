<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Tournament;
use App\User;
use Faker\Generator as Faker;

$factory->define(Tournament::class, function (Faker $faker) {
    return [
        'name' => $faker->words(2, true),
        'discipline' => 'Table tennis',
        'organizer_id' => factory(User::class),
        'time' => $faker->dateTimeBetween('+10 days', '+30 days'),
        'location' => 'Poznan',
        'lat' => '52.01',
        'lng' => '19.02',
        'max_participants' => $faker->numberBetween(2, 32),
        'application_deadline' => $faker->dateTimeBetween('now', '+9 days')
    ];
});
