<?php
require_once(__DIR__ . "/inc/bootstrap.php");
#Configuration -> inc/config.php

if (getenv("PHP_ENV") == "prd" && $_SERVER['REMOTE_ADDR'] != "200.146.225.145") { # Uma forma de proteger a aplicação
    die;
}

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);

if ((isset($uri[3]) && ($uri[4] != 'git' && $uri[4] != 'config')) || !isset($uri[5])) {
    header("HTTP/1.1 404 Not Found");
    exit();
}

require_once __DIR__ . "/Controller/ajax-handlers/GitController.php";
require_once __DIR__ . "/Controller/ajax-handlers/ConfigController.php";

if ($uri[4] == 'git') {
    $controller = new GitController();
}else if($uri[4] == 'config'){
    $controller = new ConfigController();
}

$strMethodName = $uri[5] . 'Action';
$controller->{$strMethodName}();