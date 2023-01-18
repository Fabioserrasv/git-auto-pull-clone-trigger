<?php
define("PROJECT_ROOT_PATH", __DIR__ . "/../");
 
// include main configuration file
require_once PROJECT_ROOT_PATH . "/inc/config.php";
 
// include the base controller file
require_once PROJECT_ROOT_PATH . "/Controller/ajax-handlers/BaseController.php";
 
// include the use model file
require_once PROJECT_ROOT_PATH . "/Models/BaseModel.php";
require_once PROJECT_ROOT_PATH . "/Models/Terminal.php";
require_once PROJECT_ROOT_PATH . "/Models/Git.php";
require_once PROJECT_ROOT_PATH . "/Models/Config.php";
