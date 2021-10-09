<?php

namespace app\controllers;

use app\classes\View;

class User
{
    public function index()
    {
        View::setView('user', [
           'title' => 'User',
        ]);
    }

    public function show($params)
    {
        if (!isset($params['user'])) {
            return redirect('/');
        }

        read('users', 'id,firstName,lastName');

        where('id', $params['user']);

        $user = execute(isfetchAll:false);

        // $user = findBy('id', $params['user'], 'users');

        if (!$user) {
            return redirect('/');
        }

        View::setView('user', [
            'title' => 'User',
            'user' => $user
        ]);
    }


    public function edit($args)
    {
        $auth = sessionData(LOGGED_SESSION);

        if (!$auth) {
            return redirect('/');
        }

        // $user = findBy('id', $auth->id, 'users', 'id,firstName,lastName,email');
        read('users', 'users.id as idUser,firstName,lastName,email,path');
        tableJoin('photos', 'id', 'left outer');
        where('users.id', $args['user']);
        $user = execute(isfetchAll:false);

        dd($user);


        View::setView('user_edit', [
            'title' => 'Create edit',
            'user' => $user
        ]);
    }

    public function update($args)
    {
        if (!isset($args['user'])) {
            return redirect('/');
        }

        $firstName = filter_input(INPUT_POST, 'firstName', FILTER_SANITIZE_STRING);
        $lastName = filter_input(INPUT_POST, 'lastName', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);

        $updated = update('users', [
            'firstName' => $firstName,
            'lastName' => $lastName,
            'email' => $email,
        ], ['id' => $args['user']]);

        if ($updated) {
            setMessageAndRedirect(
                'Atualizado com sucesso',
                'edit_success',
                "/user/{$args['user']}/edit"
            );
        }

        setMessageAndRedirect(
            'Ocorreu um erro ao atualizar',
            'edit_error',
            "/user/{$args['user']}/edit"
        );
    }

    public function create()
    {
        View::setView('user_create', [
            'title' => 'Create user',
        ]);
    }

    public function store()
    {
        $firstName = filter_input(INPUT_POST, 'firstName', FILTER_SANITIZE_STRING);
        $lastName = filter_input(INPUT_POST, 'lastName', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

        $validate = validate(
            [
                "firstName" => 'required',
                "lastName" => 'required',
                "email" => 'email|required',
                "password" => 'maxlen:5|required',
            ],
            persistFields:true,
            checkCsrf: true
        );

        if (!$validate) {
            return redirect('/user/create');
        }

        $created = create('users', [
           'firstName' => $firstName,
           'lastName' => $lastName,
           'email' => $email,
           'password' => password_hash($password, PASSWORD_DEFAULT)
        ]);

        if ($created) {
            return redirect('/');
        }

        setMessageAndRedirect('Ocorreu um erro ao cadastrar, tente novamente em alguns minutos', 'message_store_error', '/user/create');
    }

    public function destroy($args)
    {
        if (!isset($args['user'])) {
            return redirect('/');
        }

        $deleted = delete('users', ['id' => $args['user']]);


        if ($deleted) {
            return setMessageAndRedirect('Deletado com sucesso', 'message_success', '/');
        }

        return setMessageAndRedirect('Ocorreu um erro ao deletar, tente novamente em alguns segundos', 'message_error', '/');
    }
}
