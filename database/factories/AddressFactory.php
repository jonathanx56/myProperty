<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Address;
use Faker\Generator as Faker;

$factory->define(Address::class, function (Faker $faker) {
    return [
        'address' => $faker->address,
        'number' => $faker->buildingNumber,
        'neighborhood' => $faker->secondaryAddress,
        'complement' => $faker->streetAddress,
        'zip_code' => $faker->postcode,
    ];
});
