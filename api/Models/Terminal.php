<?php

class Terminal extends BaseModel{
    public function execResult($command) {
        $result = array();
        exec($command . " 2>&1", $result);
        $r = '';
        foreach ($result as $line) {
            $r .= ($line . "\n");
        }
        return $r;
    }
}
