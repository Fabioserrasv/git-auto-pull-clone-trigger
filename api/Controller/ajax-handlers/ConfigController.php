<?php

class ConfigController extends BaseController {
    public function addOrModifyAction() {
        try {
            if (!isset($_POST["password"])) {
                return;
            }
            $strErrorDesc = '';
            $config = new Config();

            $variables = json_decode($_POST["variables"], true);

            $response = $config->addOrModifyVariables($variables);

            if ($response) {
                $responseData = "Registro atualizado com sucesso.";
            } else {
                $strErrorDesc = "Algo deu errado.";
                $strErrorHeader = 'HTTP/1.1 400 Bad Request';
            }
        } catch (Exception $e) {
            $strErrorDesc = $e->getMessage() . 'Something went wrong! Please contact support.';
            $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
        }

        if (!$strErrorDesc) {
            $this->sendOutput(
                json_encode(['status' => 200, "message" => $responseData]),
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendOutput(
                json_encode(array('error' => $strErrorDesc)),
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }

    public function getVariablesAction() {
        try {
            if (!isset($_GET["p"])) {
                return;
            }
            $config = new Config($_GET["p"]);
            $strErrorDesc = '';
            $response = $config->getVariablesArrayFromConfigFile();

            if (!$response) {
                $strErrorDesc = $response['message'];
                $strErrorHeader = 'HTTP/1.1 400 Bad Request';
            }
        } catch (Exception $e) {
            $strErrorDesc = $e->getMessage() . 'Something went wrong! Please contact support.';
            $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
        }

        if (!$strErrorDesc) {
            $this->sendOutput(
                json_encode(['status' => 200, "variables" => $response]),
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendOutput(
                json_encode(array('error' => $strErrorDesc)),
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }
}
