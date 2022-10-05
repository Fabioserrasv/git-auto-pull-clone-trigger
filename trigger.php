<?php
require_once __DIR__ .  '/inc/config.php';

#Configuration -> inc/config.php
$rep_host = REP_HOST;
$rep_name = REP_NAME;
$rep_branch = REP_BRANCH;
$localfolder = LOCALFOLDER;
$pat = PAT;
$pass = PASS;

if (getenv("PHP_ENV") == 'prd') { # Validando ambiente, para ser utilizado em outros sistemas essa verificação irá mudar.
    if (!isset($_GET['p'])) {
        echo "Permission denied.";
        die;
    } else {
        if ($_GET['p'] != $pass) { # Validando senha de segurança da route.
            echo "Permission denied.";
            die;
        }
    }
}

try {
    $resJson = [];
    $today = new DateTime('now', new DateTimeZone('America/Sao_Paulo')); # UTC -3
    $today = $today->format('d/m/Y H:i:s');
    $res = "[{$today}]:\n";
    $resJson["datetime"] = $today;

    if (isset($_GET["revert"])) {
        $res .= execResult("git -C {$localfolder}/ reset --hard HEAD~" . $_GET["revert"]);
        $resJson["command_exec"] = "git -C {$localfolder}/ reset --hard HEAD~" . $_GET["revert"];
    } else {
        if (!file_exists($localfolder)) {
            $b = ($rep_branch != "" ? "-b " : "");
            $res .= execResult("git clone {$b}{$rep_branch} https://{$pat}github.com/{$rep_host}/{$rep_name}.git {$localfolder}");
            $resJson["command_exec"] = "git clone https://<PAT>github.com/{$rep_host}/{$rep_name}.git {$rep_branch}";
        } else {
            $res .= execResult("git -C {$localfolder}/ pull https://{$pat}github.com/{$rep_host}/{$rep_name}.git {$rep_branch}");
            $resJson["command_exec"] = "git -C {$localfolder}/ pull https://<PAT>github.com/{$rep_host}/{$rep_name}.git {$rep_branch}";
        }
    }

    $resJson["message"] = $res;
    generateLogs($res, ($resJson));
    echo "Done";
} catch (Exception $e) {
    $today = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
    $today = $today->format('d/m/Y H:i:s');
    $res = "[{$today}]:\n" . $e->getMessage();
    $resJson["datetime"] = $today;
    $resJson["command_exec"] = "";
    $resJson["message"] = $e->getMessage();
    generateLogs($res, ($resJson));
    echo "Error";
}
function execResult($command) {
    $result = array();
    exec($command . " 2>&1", $result);
    $r = '';
    foreach ($result as $line) {
        $r .= ($line . "\n");
    }
    return $r;
}
function generateLogs($res, $resJson) {
    $oldContent = '';
    echo $res . "<br>";
    # 1 - Generate log file normally
    if (file_exists('./log_output.log')) {
        $oldContent = file_get_contents('./log_output.log');
    }
    $fp = fopen("./log_output.log", "w");
    fwrite($fp, $res . "\n" . $oldContent);
    fclose($fp);
    # 2 - Generate log file as json
    if (file_exists('./log_output.json')) {
        $oldContent = file_get_contents('./log_output.json');
        $oldContent = json_decode($oldContent, true);
        $oldContent = array_reverse($oldContent);
        $oldContent[] = $resJson;
        $resJson = json_encode(array_reverse($oldContent));
    } else {
        $a = [];
        $a[] = $resJson;
        $resJson = json_encode($a);
    }
    $fpj = fopen("./log_output.json", "w");
    fwrite($fpj, $resJson);
    fclose($fpj);
}
