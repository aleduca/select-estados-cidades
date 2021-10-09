<?php

namespace app\controllers;

class Users
{
    public function index()
    {
        read('users', 'id,firstName,lastName');
        $users = execute();

        echo json_encode($users);
    }
}
