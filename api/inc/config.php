<?php
$configVariables = parse_ini_file(__DIR__ . '/config.cfg');

define('REP_HOST', $configVariables["REP_HOST"]); # Nome do usuário host.
define('REP_NAME', $configVariables["REP_NAME"]); # Nome do repositório.
define('REP_BRANCH', $configVariables["REP_BRANCH"]); # Nome da branch.
define('LOCALFOLDER', PROJECT_ROOT_PATH . "/../../" . $configVariables["LOCALFOLDER"]); # '/../../aso_deploy' Nome da pasta que deseja clonar/pull.
define('PAT', getenv('PAT_GIT') . "@"); # Caso o repositório seja publico, não é necessário pat.
define('PASS', "ugOMs6M81");# Senha de segurança da route.
