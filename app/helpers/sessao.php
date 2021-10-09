<?php

function logged($sessionIndex):bool
{
    return isset($_SESSION[$sessionIndex]);
}

function sessionData($sessionIndex)
{
    if (isset($_SESSION[$sessionIndex])) {
        return $_SESSION[$sessionIndex];
    }
    return false;
}
