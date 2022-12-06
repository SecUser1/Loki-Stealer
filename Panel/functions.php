<?php
@error_reporting(0);
@set_time_limit(0);
@ini_set('max_execution_time', 0);
@ini_set('max_input_vars', 100000000);
@ini_set("memory_limit","500M");

function base64decrypt($str){
    $sub1 = str_replace("-", "+", $str);
    $sub2 = str_replace("_", "/", $sub1);
    $sub3 = str_replace(".", "=", $sub2);
    return base64_decode($sub3);
}

function TRAFFIC_DECRYPT($bytes, $key){
    $out = '';
    for($i = 0; $i < strlen($bytes); $i++){
        $out .= $bytes[$i] ^ $key[$i % 256];
    }
    
    return $out;
}
?>