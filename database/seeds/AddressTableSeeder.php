<?php

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Address;

class AddressTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Country::class, 2)->create()->each(function ($country) {

            $state = factory(State::class)->make();
            $country->state()->save($state, 3)->each(function ($state) {

                $city = factory(City::class)->make();
                $state->city()->save($city, 3)->each(function ($city, $state) {

                    factory(Address::class, 2)->create([
                        'state_id' => $city->state_id,
                        'city_id' => $city->id,
                    ]);
                });
            });
        });
    }
}
