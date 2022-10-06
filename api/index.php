<?php
require_once(__DIR__ . "/inc/bootstrap.php");
#Configuration -> inc/config.php

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );

if ((isset($uri[3]) && $uri[4] != 'git') || !isset($uri[5])) {
    header("HTTP/1.1 404 Not Found");
    exit();
}
 
require_once __DIR__ . "/Controller/ajax-handlers/GitController.php";
 
$git = new GitController();
$strMethodName = $uri[5] . 'Action';
$git->{$strMethodName}();
