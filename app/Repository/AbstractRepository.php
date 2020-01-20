<?php

namespace App\Repository;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractRepository{

    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Faz o condicional da pesquisa
     * Separa as condições recebidas na url sepadas por ";" e armazena no array expressions
     *
     *
     * @param [type] $conditions
     * @return void
     */

    public function selectConditions($conditions)
    {
        // title:Home;description:ispsun
        $expressions = explode(';', $conditions);
        // $expressions['title:Home', 'description:ipsun'];
        foreach ($expressions as $e) {
            $exp = explode(':', $e);
            // $exp['title', 'Home'] or $exp['title', 'like', 'ipsun']
            $this->model = $this->model->where($exp[0], $exp[1], $exp[2]);
        }

    }

    //Select the fields that will or will not appear in the search.
    //Seleciona os campos que irão ou não aparecer na pesquisa
    public function selectFilter($fields)
    {
        $this->model = $this->model->selectRaw($fields);
        return $this->model;
    }

    public function getResult()
    {
        return $this->model;
    }
}
