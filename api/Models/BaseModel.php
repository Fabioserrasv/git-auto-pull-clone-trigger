<?php

class BaseModel{
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