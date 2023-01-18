<?php

class Config extends BaseModel {

    public function addOrModifyVariables($variables) {
        foreach ($variables as $var) {
            $response = $this->addOrModifyVariable($var["name"], $var["value"]);
        }
        $texto = 'Variaveis de ambiente foram alteradas com sucesso. Por favor cheque se está no ambiente correto antes de prosseguir com ações.';
        $resJson["command_exec"] = $texto;
        $this->generateLogs($texto, $resJson);
        return $response;
    }
    public function addOrModifyVariable($name, $value) {
        $variables = $this->getVariablesArrayFromConfigFile();
        $variables[strtoupper($name)] = $value;
        $rawContent = '';

        foreach ($variables as $nameVar => $valueVar) {
            $nameContent = strtoupper($nameVar);
            $rawContent .= "{$nameContent}={$valueVar}\n";
        }

        return $this->writeInConfigFile($rawContent);
    }

    // public function deleteVariable($name, $value){}

    public function writeInConfigFile($content) {
        $file = fopen(__DIR__ . "/../inc/config.cfg", 'w');
        fclose($file);
        $file = fopen(__DIR__ . "/../inc/config.cfg", 'w');
        fwrite($file, $content);
        return fclose($file);
    }

    public function getVariablesArrayFromConfigFile(): array {
        return parse_ini_file(__DIR__ . '/../inc/config.cfg');
    }
}
