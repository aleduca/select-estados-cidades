<?php
namespace app\controllers;

use app\classes\View;

class Forget
{
    public function create()
    {
        View::setView('forget', [
            'title' => 'Forget Password',
         ]);
    }


    public function store()
    {
        $isEmail = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

        if (!$isEmail) {
            setMessageAndRedirect(
                'Email inválido',
                'message_forget',
                '/forget/password'
            );
        }

        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

        read('users', 'id,firstName,lastName,email');

        where('email', '=', $email);

        $user = execute(isfetchAll:false);

        if (!$user) {
            return setMessageAndRedirect(
                'Não encontramos o usuário com o email '.$email,
                'message_forget',
                '/forget/password'
            );
        }
    }
}
