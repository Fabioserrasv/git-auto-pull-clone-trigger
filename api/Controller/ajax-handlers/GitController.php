<?php

class GitController extends BaseController{

    public function pullAction(){
        try{
            if(!isset($_GET["p"])){
                return;
            }
            $trigger = new Git($_GET["p"]);
            $strErrorDesc = '';
            $response = $trigger->pullChanges();
            
            if($response['status']){
                $responseData = $response['message'];
            }else{
                $strErrorDesc = $response['message'];
                $strErrorHeader = 'HTTP/1.1 400 Bad Request';
            }
        }catch(Exception $e){
            $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
            $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
        }

        if (!$strErrorDesc) {
            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendOutput(json_encode(array('error' => $strErrorDesc)), 
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }

    public function revertAction(){
        try{
            if(!isset($_GET["p"])){
                return;
            }
            if(!isset($_GET["amount"])){
                return;
            }
            $trigger = new Git($_GET["p"]);
            $strErrorDesc = '';
            $response = $trigger->revertChanges($_GET["amount"]);
    
            if($response['status']){
                $responseData = $response['message'];
            }else{
                $strErrorDesc = $response['message'];
                $strErrorHeader = 'HTTP/1.1 400 Bad Request';
            }
        }catch(Exception $e){
            $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
            $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
        }

        if (!$strErrorDesc) {
            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendOutput(json_encode(array('error' => $strErrorDesc)), 
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }
}
