<?php

class Git extends Terminal {
    protected $repHost = REP_HOST;
    protected $repName = REP_NAME;
    protected $repBranch = REP_BRANCH;
    protected $localfolder = LOCALFOLDER;
    protected $pat = PAT;
    protected $pass = PASS;

    public function __construct($password) {
        if (!isset($password)) {
            echo "Permission denied.";
            die;
        } else {
            if ($password != $this->pass) { # Validando senha de segurança da route.
                echo "Permission denied.";
                die;
            }
        }
    }

    public function pullChanges() {
        try {
            if (!file_exists($this->localfolder)) {
                $b = ($this->repBranch != "" ? "-b " : "");
                $res = $this->execResult("git clone {$b}{$this->repBranch} https://{$this->pat}github.com/{$this->repHost}/{$this->repName}.git {$this->localfolder}");
                $resJson["command_exec"] = "git clone https://<PAT>github.com/{$this->repHost}/{$this->repName}.git {$this->repBranch}";
            } else {
                $res = $this->execResult("git -C {$this->localfolder}/ pull https://{$this->pat}github.com/{$this->repHost}/{$this->repName}.git {$this->repBranch}");
                $resJson["command_exec"] = "git -C {$this->localfolder}/ pull https://<PAT>github.com/{$this->repHost}/{$this->repName}.git {$this->repBranch}";
            }

            $this->generateLogs($res, $resJson);
            return ['status' => true, 'message' => $res];
        } catch (Exception $e) {
            $error = $e->getMessage();
            $resJson["command_exec"] = "";
            $resJson["message"] = $error;
            $this->generateLogs($error, ($resJson));
            return ['status' => false, 'message' => $error];
        }
    }

    public function revertChanges($commit) {
        try {
            if ($commit == '') {
                return ['status' => false, 'message' => "You have to specify a commit."];
            }
            $res = $this->execResult("git -C {$this->localfolder}/ reset --hard " . $commit);
            $resJson["command_exec"] = "git -C {$this->localfolder}/ reset --hard " . $commit;

            $this->generateLogs($res, $resJson);
            return ['status' => true, 'message' => $res];
        } catch (Exception $e) {
            $error = $e->getMessage();
            $resJson["command_exec"] = "";
            $resJson["message"] = $error;
            $this->generateLogs($error, ($resJson));
            return ['status' => false, 'message' => $error];
        }
    }

    public function listCommits($amount) {
        try {
            if ($amount <= 0) {
                return ['status' => false, 'message' => 'Amount cannot be negative.'];
            }

            if (file_exists($this->localfolder)) {
                $res = $this->execResult("git -C {$this->localfolder}/ log --pretty=format:\"%h - %an, %ar : %s\" -{$amount}");
                $res = explode("\n", $res);
                $resJson["command_exec"] = "git reflog --pretty=format:\"%h - %an, %ar : %s\" -{$amount}";
            } else {
                return ['status' => false, 'message' => 'Directory not found.'];
            }

            //$this->generateLogs($res, $resJson);
            return ['status' => true, 'message' => json_encode($res)];
        } catch (Exception $e) {
            $error = $e->getMessage();
            $resJson["command_exec"] = "";
            $resJson["message"] = $error;
            $this->generateLogs($error, ($resJson));
            return ['status' => false, 'message' => $error];
        }
    }

    public function listLog($amount) {
        try {
            if ($amount <= 0) {
                return ['status' => false, 'message' => 'Amount cannot be negative.'];
            }

            if (file_exists(PROJECT_ROOT_PATH . '/logs/log_output.log')) {
                $content = file_get_contents(PROJECT_ROOT_PATH . '/logs/log_output.json');
                $content = array_slice(json_decode($content, true), 0, $amount);
            } else {
                return ['status' => false, 'message' => 'No log file was found.'];
            }

            //$this->generateLogs($res, $resJson);
            return ['status' => true, 'message' => ($content)];
        } catch (Exception $e) {
            $error = $e->getMessage();
            $resJson["command_exec"] = "";
            $resJson["message"] = $error;
            $this->generateLogs($error, ($resJson));
            return ['status' => false, 'message' => $error];
        }
    }

    protected function generateLogs($res, $resJson) {
        $today = new DateTime('now', new DateTimeZone('America/Sao_Paulo')); # UTC -3
        $today = $today->format('d/m/Y H:i:s');
        $resJson["datetime"] = $today;
        $resJson["message"] = $res;
        $resFinal = "[{$today}]:\n";
        $resFinal .= $res;

        $oldContent = '';
        # 1 - Generate log file normally
        if (file_exists(PROJECT_ROOT_PATH . '/logs/log_output.log')) {
            $oldContent = file_get_contents(PROJECT_ROOT_PATH . '/logs/log_output.log');
        }
        $fp = fopen(PROJECT_ROOT_PATH . '/logs/log_output.log', "w");
        fwrite($fp, $resFinal . "\n" . $oldContent);
        fclose($fp);
        # 2 - Generate log file as json
        if (file_exists(PROJECT_ROOT_PATH . '/logs/log_output.json') && file_get_contents(PROJECT_ROOT_PATH . '/logs/log_output.json') != '') {
            $oldContent = file_get_contents(PROJECT_ROOT_PATH . '/logs/log_output.json');
            $oldContent = json_decode($oldContent, true);
            $oldContent = array_reverse($oldContent);
            $oldContent[] = $resJson;
            $resJson = json_encode(array_reverse($oldContent));
        } else {
            $a = [];
            $a[] = $resJson;
            $resJson = json_encode($a);
        }
        $fpj = fopen(PROJECT_ROOT_PATH . "/logs/log_output.json", "w");
        fwrite($fpj, $resJson);
        fclose($fpj);
    }
}
