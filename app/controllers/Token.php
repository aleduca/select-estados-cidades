<?php

namespace app\controllers;

use app\classes\View;

class Token
{
    public function index()
    {
        View::setView('token', [
            'title' => 'Token Invalid',
        ]);
    }
    
}