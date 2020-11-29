<?php
session_start();
require_once 'config.php';

if(empty($_SESSION['order_id'])){
    header('Refresh:0;url=\'./index.php\'');
    exit();
}

$data = file_get_contents('https://api.xiuxiu888.com/ispay?id='.ID.'&token=' . TOKEN .'&order_id='.$_SESSION['order_id']);

$data = json_decode($data,true);

if($data['status'] != '1')
{
    header('Refresh:0;url=\'./index.php\'');
    exit();
} else {
    $comment = $_SESSION['comment'];
    $order_id = $_SESSION['order_id'];
    $money = $_SESSION['money'];
    $time = time();
    session_destroy();    
    $arr = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM `order` WHERE order_id='$order_id'"));
    if(empty($arr['time'])){
        mysqli_query($conn,"INSERT INTO `order` VALUES('0','$money','$order_id','$comment','$time','$ip')");
    }
}
?>
<html>
    <head>
        <title>贫穷网</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, user-scalable=no">
        <meta http-equiv="Cache-Control" content="no-siteapp" />
        <meta name="description" content="贫穷网" />
        <meta name="keywords" content="soxft,没钱,贫穷,xcsot" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <script src="https://cdn.bootcdn.net/ajax/libs/jquery/3.5.1/jquery.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    </head>
    <body style='max-width:90%;transform:translate(5%,20px);background-color:#F5F5F5;'>
            <div class="jumbotron" style='transform:translateY(3%);'>
                <div style='max-width:84%;transform:translate(8%);'>
                    <h1>贫穷网</h1>
                    <p>贫穷限制了我的想象力，创造力，巧克力。希望您帮我摆脱贫穷。</p>
                </div>
            </div>
            <div style='Height:5px'></div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                         <h3 class="panel-title">贫穷网</h3>
                    </div>
                     <div style='Height:30px'></div>
                    <div class="panel-body" style='max-width:90%;transform:translateX(5%);'>
                        <center>
                            <h2>感谢您的扶贫</h2>
                        </center>
                    </div>
                     <div style='Height:40px'></div>
                </div>
    </body>
    <footer>
        <div style='Height:5px'></div>
        <center><div class="well well-sm">Copyright © 2020 - <?php echo date('Y') ?> XCSOFT All Rights Reserved.</div></center>
    </footer>