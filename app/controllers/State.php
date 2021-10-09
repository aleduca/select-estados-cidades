<?php

namespace app\controllers;

class State
{
    public function index()
    {
        read('estado', 'id,nome');


        $states = execute();


        echo json_encode($states);
    }
}
