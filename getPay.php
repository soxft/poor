<?php
require_once 'config.php';
session_start();
$trade_no = $_GET['outTradeNo'];
$sql = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM `content` WHERE `outTradeNo`='$trade_no'"))['ispaid'];
if($sql == 'true')
{
    echo 'true';
    session_destroy();
} else{
    echo 'false';
}