<?php

namespace app\controllers;

use app\classes\View;

class Maintenance
{
    public function index()
    {
        View::setView('maintenance', [
            'title' => 'Maintenance'
         ]);
    }
}
