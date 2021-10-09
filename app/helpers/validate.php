<?php

function validate(array $validations, bool $persistFields = false, bool $checkCsrf = false)
{
    $result = [];
    $param = '';
    foreach ($validations as $field => $validate) {
        $result[$field] = (!str_contains($validate, '|')) ?
            singleValidation($validate, $field, $param) :
            multipleValidations($validate, $field, $param);
    }

    if ($persistFields) {
        setOld();
    }

    if ($checkCsrf) {
        checkCsrf();
        // redirect('/token');
        // die();
    }

    if (in_array(false, $result)) {
        return false;
    }

    return $result;
}

function singleValidation($validate, $field, $param): bool|string
{
    if (str_contains($validate, ':')) {
        [$validate, $param] = explode(':', $validate);
    }
    return $validate($field, $param);
}

function multipleValidations($validates, $field, $param): bool|string
{
    $result = [];
    $explodePipeValidate = explode('|', $validates);
    foreach ($explodePipeValidate as $validate) {
        if (str_contains($validate, ':')) {
            [$validate, $param] = explode(':', $validate);
        }

        $result[$field] = $validate($field, $param);

        if (isset($result[$field]) and $result[$field] === false) {
            break;
        }
    }
    return $result[$field];
}

function required($field)
{
    if (empty($_POST[$field])) {
        setFlash($field, 'Esse campo é obrigatorio');
        return false;
    }

    return filter_input(INPUT_POST, $field, FILTER_SANITIZE_STRING);
}

function email($field)
{
    $email = filter_input(INPUT_POST, $field, FILTER_VALIDATE_EMAIL);

    if (!$email) {
        setFlash($field, 'Email inválido');
        return false;
    }

    return filter_input(INPUT_POST, $field, FILTER_SANITIZE_STRING);
}

function maxlen($field, $param)
{
    $data = filter_input(INPUT_POST, $field, FILTER_SANITIZE_STRING);

    if (strlen($data) > $param) {
        setFlash($field, 'O campo não pode ter mais que '.$param.' caracteres');
        return false;
    }

    return $data;
}

function error($data)
{
    return in_array(false, $data);
}
