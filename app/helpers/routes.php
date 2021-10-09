<?php

return[
    'POST' => [
        "/login/store" => "Login@store",
        "/user/store" => "User@store",
        "/contact" => "Contact@store",
        "/user/image/update" => "UserImage@update",
        "/user/[0-9]+" => "User@update",
        '/forget/password' => "Forget@store"
    ],
    'GET' => [
        "/" => "Home@index",
        "/contact" => "Contact@index",
        "/login" => "Login@index",
        "/api/states" => "State@index",
        "/api/cities" => "City@index",
        "/token" => "Token@index",
        "/logout" => "Login@destroy",
        "/user/[a-z0-9]+" => "User@show",
        "/users" => "Users@index",
        "/user/edit" => "User@edit",
        "/user/create" => "User@create",
        "/user/[0-9]+/edit" => "User@edit",
        "/user/[0-9]+/delete" => "User@destroy",
        '/forget/password' => "Forget@create"
    ]
];
