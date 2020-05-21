<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\UserProfile;
use Faker\Generator as Faker;

$factory->define(UserProfile::class, function (Faker $faker) {
    return [
        'phone'           => $faker->phoneNumber,
        'mobile_phone'    => $faker->phoneNumber,
        'about'           => $faker->text($maxNbChars = 50),
        'social_networks' => serialize($faker->freeEmailDomain),
    ];
});
