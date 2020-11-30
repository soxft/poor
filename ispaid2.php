<?php
require_once 'config.php';

$data = file_get_contents('https://api.xiuxiu888.com/ispay?id='.ID.'&token=' .TOKEN .'&order_id='.$_GET['order_id']);

$data = json_decode($data,true);

echo $data['status'];

session_start();
$uid = $_SESSION['uid'];
$comment = $_SESSION['comment'];
$money = $_SESSION['money'];
$time = time();
$arr = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM `order` WHERE uid='$uid'"));
if(empty($arr['time']) && (int)$data['status'] >= 1 && !empty($money)){
    mysqli_query($conn,"INSERT INTO `order` VALUES('$uid','$money','$comment','$time','$ip')");
    
    $numx = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM `config` WHERE `type`='num'"))['content'];
    $moneyx = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM `config` WHERE `type`='money'"))['content'];
    $moneyx += $money;
    $numx++;
    mysqli_query($conn,"UPDATE `config` SET `content`='$moneyx' WHERE `type`='money'");  
    mysqli_query($conn,"UPDATE `config` SET `content`='$numx' WHERE `type`='num'"); 
    //更新统计
}
session_destroy();