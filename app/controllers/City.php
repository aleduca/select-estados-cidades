<?php

namespace app\controllers;

class City
{
    public function index()
    {
        $state = filter_input(INPUT_GET, 'uf', FILTER_SANITIZE_STRING);

        read('cidade', 'id,nome,uf');

        where('uf', $state);

        $cities = execute();

        echo json_encode($cities);
    }
}
