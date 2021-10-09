<?php

function setOld()
{
    if (!isset($_SESSION['old'])) {
        $_SESSION['old'] = $_POST ?? [];
    }
}


function getOld($key)
{
    if (isset($_SESSION['old'])) {
        $flash = $_SESSION['old'];
        unset($_SESSION['old']);

        return $flash[$key] ?? '';
    }
}

