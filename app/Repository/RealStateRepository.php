<?php

namespace App\Repository;

class RealStateRepository extends AbstractRepository{

    private $location;

    public function setLocation(array $data): self
    {
        $this->location = $data;

        return $this;
    }

    public function getResult()
    {
        $location = $this->location;

        // $address diz repeito ao método de relacionamento entre real_state e adress
        return $this->model->whereHas('address', function($address) use($location){
            if($location['state'] || $location['city'])
            {
                $address->where('state_id', $location['state'])
                        ->where('city_id', $location['city']);
            }

        });
    }
}
