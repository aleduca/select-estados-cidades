<?php

require 'bootstrap.php';

use app\classes\View;
use League\Plates\Engine;

try {
    router();

    if (isAjax()) {
        die();
    }

    $templates = new Engine(ROOT.DIRECTORY_SEPARATOR."app/views");

    echo $templates->render(View::getView(), View::getData());

    // require "../app/views/master.php";
} catch (Exception $e) {
    var_dump($e->getMessage());
}
