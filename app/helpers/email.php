<?php

use app\classes\View;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

function phpmailerConfig(): PHPMailer
{
    $phpMailer = new PHPMailer();
    $phpMailer->SMTPDebug = SMTP::DEBUG_SERVER;
    $phpMailer->isSMTP();
    $phpMailer->Host = $_ENV['MAILER_SMTP'];
    $phpMailer->SMTPAuth = true;
    $phpMailer->Username = $_ENV['MAILER_USERNAME'];
    $phpMailer->Password = $_ENV['MAILER_PASSWORD'];
//    $phpMailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $phpMailer->Port = $_ENV['MAILER_PORT'];
    return $phpMailer;
}

function phpMailerSend(stdClass $email)
{
    $phpMailer = phpmailerConfig();

    try {
        checkPropertiesEmailData($email);

        $phpMailer->setFrom($email->fromEmail, $email->fromName);
        $phpMailer->addAddress($email->toEmail, $email->toName);

        $phpMailer->Body  = (isset($email->template)) ?
        phpMailerTemplate($email) :
        $email->message;

        $phpMailer->isHTML(true);
        $phpMailer->CharSet = "UTF-8";
        $phpMailer->Subject = $email->subject;
        $phpMailer->AltBody = 'This is the body in plain text for non-HTML mail clients';

        return $phpMailer->send();
    } catch (Exception $e) {
        dd($e);
    }
}

function checkPropertiesEmailData(stdClass $email)
{
    $propertiesRequired = ['toName','toEmail','fromName','fromEmail','subject','message'];
    $emailVars = get_object_vars($email);
    unset($emailVars['template']);

    foreach ($propertiesRequired as $var) {
        if (!in_array($var, array_keys($emailVars))) {
            throw new Exception("Propriedade {$var} obrigatória");
        }
    }
}


function phpMailerTemplate(stdClass $email)
{
    // $newTamplate = str_replace(array_keys($emailVars), array_values($emailVars), $template);
    $template = file_get_contents(ROOT."/app/views/email/{$email->template}.html");
    $emailVars = get_object_vars($email);
    $vars = [];
    foreach ($emailVars as $index => $var) {
        $vars["@{$index}"] = $var;
    }
    return str_replace(array_keys($vars), array_values($vars), $template);
}
