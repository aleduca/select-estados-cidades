<?php

function routes():array
{
    return require ROOT.'/app/helpers/routes.php';
}

function loadController($matchedUri, $params = [])
{
    [$controller,$method] = explode('@', array_values($matchedUri)[0]);
    $controller = CONTROLLER_PATH.$controller;

    if (!class_exists($controller)) {
        throw new Exception("Controller {$controller} não existe");
    }

    $controllerInstance = new $controller;

    if (!method_exists($controllerInstance, $method)) {
        throw new Exception("Método {$method} não existe no controller {$controller}");
    }

    $controller = $controllerInstance->$method($params);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        die('Erro');
    }

    return $controller;
}

function exactMatchArrayRoutes($uri, $routes):array
{
    if (array_key_exists($uri, $routes)) {
        return [$uri => $routes[$uri]];
    }
    return [];
}

function regularExpressionMatchArrayRoutes($uri, $routes):array
{
    return array_filter(
        $routes,
        function ($value) use ($uri) {
            $regex = str_replace("/", "\/", ltrim($value, '/'));
            return preg_match("/^$regex$/", ltrim($uri, '/'));
        },
        ARRAY_FILTER_USE_KEY
    );
}


function params($uri, $matchedUri)
{
    if (!empty($matchedUri)) {
        $matchToGetParams = array_keys($matchedUri)[0];
        return array_diff(
            explode('/', ltrim($uri, '/')),
            explode('/', ltrim($matchToGetParams, '/'))
        );
    }
    return [];
}

function formatParams($uri, $params)
{
    $uri = explode('/', ltrim($uri, '/'));
    $paramsData = [];
    foreach ($params as $index => $param) {
        $uriIndex = $index -1;
        if (isset($uri[$uriIndex])) {
            $paramsData[$uri[$uriIndex]] = $param;
        }
    }
    return $paramsData;
}


function isInMaintenance()
{
    if ($_ENV['MAINTENANCE'] === 'true') {
        return true;
    }
    return false;
}

function router():void
{
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $requestMethod =  $_SERVER['REQUEST_METHOD'];
    $routes = routes()[$requestMethod];

    $matchedUri = exactMatchArrayRoutes($uri, $routes);

    $params = [];
    if (empty($matchedUri)) {
        $matchedUri = regularExpressionMatchArrayRoutes($uri, $routes);
        $params = params($uri, $matchedUri);
        $params = formatParams($uri, $params);
    }

    if (isInMaintenance()) {
        $matchedUri = ['/maintenance' => 'Maintenance@index'];
    }

    if (!empty($matchedUri)) {
        loadController($matchedUri, $params);
        return;
    }

    throw new Exception('Ops, algo deu errado');
}
