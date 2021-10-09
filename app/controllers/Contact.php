<?php

namespace app\controllers;

use app\classes\View;
use stdClass;

class Contact
{
    public function index()
    {
        View::setView('contact', [
            'title' => 'Contact',
        ]);
    }

    public function store()
    {
        $validated = validate([
            'name' => 'required',
            'email' => 'required|email',
            'subject' => 'required',
            'message' => 'required|maxLen:100'
        ], createOldFunction: true);

        // dd('validated', $validated);

        if (!$validated) {
            return redirect('/contact');
        }

        $email = new stdClass();
        $email->fromName = 'Alexandre';
        $email->fromEmail = 'xandecar@hotmail.com';
        $email->toName = $validated['name'];
        $email->toEmail = $validated['email'];
        $email->subject = $validated['subject'];
        $email->message = $validated['message'];
        $email->template = 'contact';

        $sent = phpMailerSend($email);
    }
}
