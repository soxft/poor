<?php
$saPrivateKey=''; //应用私钥

$alipayPublicKey='';//支付宝公钥

$appid = ''; // APPID

$notifyUrl = 'https://example.com/notify.php';     //异步回调地址

$conn = mysqli_connect('localhost','pay2','pay2','pay2');

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