<?php

define('ROOT', dirname(__FILE__, 3));
define('CONTROLLER_PATH', 'app\\controllers\\');
define('LOGGED_SESSION', 'logged');
define('URI', $_SERVER['REQUEST_URI']);
define('QUERY_STRING', $_SERVER["QUERY_STRING"] ?? '');
