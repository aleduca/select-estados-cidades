<?php

namespace app\controllers;

use app\classes\View;

class Home
{
    public function index()
    {
        View::setView(
            'home',
            [
            'title' => 'Home',
            ]
        );
    }
}
