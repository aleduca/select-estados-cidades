<?php

namespace app\controllers;

use app\classes\View;

class Product
{
    public function index()
    {
        View::setView('products', [
            'title' => 'Products'
        ]);
    }
}
