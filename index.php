<?php
if (getenv("PHP_ENV") == "prd" && $_SERVER['REMOTE_ADDR'] != "200.146.225.145") {  # Uma forma de proteger a aplicação
    die;
}
include_once 'app.html';
