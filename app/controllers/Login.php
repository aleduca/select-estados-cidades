<?php

namespace app\controllers;

use app\classes\View;

class Login
{
    public function index()
    {
        View::setView('login', [
           'title' => 'Login',
        ]);
    }

    public function store()
    {
        if (isset($_POST['email'])) {
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

            // fazer validação
            if (empty($email) || empty($password)) {
                return setMessageAndRedirect(
                    'Usuário ou senha inválidos',
                    'message',
                    '/login'
                );
            }

            // verificar se email existe no banco de dados
            // $user = findBy(field:'email', value:$email, table:'users');
            read('users', 'users.id,firstName,lastName,email,password,path');
            tableJoin('photos', 'id');

            $user = execute(isfetchAll:false);

            if (!$user) {
                return setMessageAndRedirect(
                    'Usuário ou senha inválidos',
                    'message',
                    '/login'
                );
            }

            // verificar se password bate com o do usuário encontrado
            if (!password_verify($password, $user->password)) {
                return setMessageAndRedirect(
                    'Usuário ou senha inválidos',
                    'message',
                    '/login'
                );
            }

            // cria sessao
            $_SESSION[LOGGED_SESSION] = $user;
            return redirect('/');
        }
    }

    public function destroy()
    {
        unset($_SESSION[LOGGED_SESSION]);
        return redirect('/');
    }
}
