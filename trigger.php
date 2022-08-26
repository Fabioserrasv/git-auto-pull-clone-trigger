<?php
try {
    #Configuration
    $rep_host = ""; # Nome do usuário host.
    $rep_name = ""; # Nome do repositório.
    $rep_branch = ""; # Nome da branch.
    $localfolder = ""; # Nome da pasta que deseja clonar/pull.
    $pat = getenv('PAT_GIT') . "@" ?? ""; # Caso o repositório seja publico, não é necessário pat.
    $pass = getenv('AUTO_GIT_PASS') ?? ""; # Senha de segurança da route.

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

    $resJson = [];
    $today = new DateTime('now', new DateTimeZone('America/Sao_Paulo')); # UTC -3
    $today = $today->format('d/m/Y H:i:s');
    $res = "[{$today}]:\n";
    $resJson["datetime"] = $today;

    if (!file_exists($localfolder)) {
        $b = ($rep_branch != "" ? "-b " : "");
        $res .= exec_result("git clone {$b}{$rep_branch} https://{$pat}github.com/{$rep_host}/{$rep_name}.git {$localfolder}");
        $resJson["command_exec"] = "git clone https://<PAT>github.com/{$rep_host}/{$rep_name}.git {$rep_branch}";
    } else {
        $res .= exec_result("git -C {$localfolder}/ pull https://{$pat}github.com/{$rep_host}/{$rep_name}.git {$rep_branch}");
        $resJson["command_exec"] = "git -C {$localfolder}/ pull https://<PAT>github.com/{$rep_host}/{$rep_name}.git {$rep_branch}";
    }

    $resJson["message"] = $res;
    generate_logs($res, ($resJson));
    echo "Done";
} catch (Exception $e) {
    $today = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
    $today = $today->format('d/m/Y H:i:s');
    $res = "[{$today}]:\n" . $e->getMessage();
    $resJson["datetime"] = $today;
    $resJson["command_exec"] = "";
    $resJson["message"] = $e->getMessage();
    generate_logs($res, ($resJson));
    echo "Error";
}
function exec_result($command){
    $result = array();
    exec($command . " 2>&1", $result);
    $r = '';
    foreach ($result as $line) {
        $r .= ($line . "\n");
    }
    return $r;
}
function generate_logs($res, $resJson){
    $old_content = '';
    # 1 - Generate log file normally
    if (file_exists('./log_output.log')) {
        $old_content = file_get_contents('./log_output.log');
    }
    $fp = fopen("./log_output.log", "w");
    fwrite($fp, $res . "\n" . $old_content);
    fclose($fp);
    # 2 - Generate log file as json
    if (file_exists('./log_output.json')) {
        $old_content = file_get_contents('./log_output.json');
        $old_content = json_decode($old_content, true);
        $old_content = array_reverse($old_content);
        var_dump($old_content);
        $old_content[] = $resJson;
        $resJson = json_encode(array_reverse($old_content));
    }else{
        $a = [];
        $a[] = $resJson;
        $resJson = json_encode($a);
    }
    $fpj = fopen("./log_output.json", "w");
    fwrite($fpj, $resJson);
    fclose($fpj);
}