<?php

namespace app\classes;

use Exception;

class View
{
    private static array $data = [];
    private static string $view;

    public static function setView(string $view, array $value)
    {
        static::$data = $value;
        static::$view = $view;
    }

    public static function getData()
    {
        return static::$data;
    }

    public static function getView()
    {
        $view = ROOT.DIRECTORY_SEPARATOR."app/views/".static::$view.'.php';

        if (!file_exists($view)) {
            throw new Exception("View {$view} não existe");
        }

        return static::$view;
    }
}
