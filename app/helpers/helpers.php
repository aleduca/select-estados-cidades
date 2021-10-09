<?php

function setMessageAndRedirect($message, $indexFlash, $redirectTo)
{
    setFlash($indexFlash, $message);
    redirect($redirectTo);
}


function isAssociativeArray(array $arr): bool
{
    return array_keys($arr) !== range(0, count($arr) - 1);
}


function isAjax(): bool
{
    return (isset($_SERVER['HTTP_HTTP_X_REQUESTED_WITH']) and $_SERVER['HTTP_HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest');
}

function urlLink(string $link, string $toReplace)
{
    $linkReplaced = preg_replace(
        '/(\?|\&)?'.$toReplace.'=\w+(\?|\&)+/',
        '',
        QUERY_STRING
    );

    parse_str($linkReplaced, $outputQueryString);
    parse_str($link, $outputLink);

    $linkResult = http_build_query(array_merge($outputQueryString, $outputLink));

    return '?'.$linkResult;
}

function auth()
{
    if (isset($_SESSION[LOGGED_SESSION])) {
        return $_SESSION[LOGGED_SESSION];
    }

    return false;
}
