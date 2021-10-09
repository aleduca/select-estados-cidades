<?php

function setFlash($key, $message)
{
    if (!isset($_SESSION['flash'][$key])) {
        $_SESSION['flash'][$key] = $message;
    }
}

function getFlash($key, $style = "color:red")
{
    if (isset($_SESSION['flash'][$key])) {
        $flash = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);

        return "<div style='$style'>$flash</div>";
    }
}

