<?php
    define('ID','你的码支付ID');
    define('KEY','你的码支付密钥');
    define('TOKEN','你的码支付token');
    define('URL','https://pay.xsot.cn/');
    $conn = mysqli_connect('localhost','pay','pay','pay');
    
    if($HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"]){
    $ip = $HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"];
    }
    elseif($HTTP_SERVER_VARS["HTTP_CLIENT_IP"]){
    $ip = $HTTP_SERVER_VARS["HTTP_CLIENT_IP"];
    }
    elseif ($HTTP_SERVER_VARS["REMOTE_ADDR"]){
    $ip = $HTTP_SERVER_VARS["REMOTE_ADDR"];
    }
    elseif (getenv("HTTP_X_FORWARDED_FOR")){
    $ip = getenv("HTTP_X_FORWARDED_FOR");
    }
    elseif (getenv("HTTP_CLIENT_IP")){
    $ip = getenv("HTTP_CLIENT_IP");
    }
    elseif (getenv("REMOTE_ADDR")){
    $ip = getenv("REMOTE_ADDR");
    }
    else{
    $ip = "Unknown";
    }
?>