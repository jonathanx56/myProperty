<?php

use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Address;
use Illuminate\Database\Seeder;

class AddressTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Country::class, 1)->create()->each(function ($country) {

            $state = factory(State::class)->make();
            $country->state()->save($state)->each(function ($state) {

                $city = factory(City::class)->make();
                $state->city()->save($city)->each(function ($city, $state) {

                    factory(Address::class)->create([
                        'state_id' => $city->state_id,
                        'city_id' => $city->id,
                    ]);
                });
            });
        });
    }
}
