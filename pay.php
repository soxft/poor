<?php
require_once 'config.php';
session_start();

$comment = $_SESSION['comment'];
$qrcode = $_SESSION['qrcode'];
$order_id = $_SESSION['order_id'];
$money = $_SESSION['money'];
$endTime = $_SESSION['endTime'];
$qrcode = $_SESSION['qrcode'];
if(empty($endTime)){
    header('Refresh:0;url=\'./index.php\'');
    exit();
}
if($endTime <= time())
{
    session_destroy();
    header('Refresh:0;url=\'./index.php\'');
    exit();
}
?>
<html>
    <head>
        <title>使用支付宝支付</title>
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
    <body style='max-width:70%;transform:translate(21%,20px);background-color:#F5F5F5;'>
            <div style='Height:5px'></div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                         <h3 class="panel-title">使用支付宝支付</h3>
                    </div>
                     <div style='Height:30px'></div>
                    <div class="panel-body" style='max-width:90%;transform:translateX(5%);'>
                        <center>
                            <div id='notice' ></div>
                            <h3>请使用支付宝支付<?php echo $money ?>元</h3>
                            <div id='qr'><img src='<?php echo $qrcode ?>'></div>
                            <h4 id='timer'>请在null分null秒内支付</h4>
                            <h4>支付完成后请耐心等待5秒</h4>
                            <h5>订单号: <?php echo $order_id ?></h5>
                        </center>
                    </div>
                     <div style='Height:30px'></div>
                </div>
                <script>
                pass = false;
                  var maxtime = <?php echo $endTime - time()?>; // 
                  function CountDown() {
                      if (maxtime >= 0) {
                          minutes = Math.floor(maxtime / 60);
                          seconds = Math.floor(maxtime % 60);
                          msg = "请在" + minutes + "分" + seconds + "秒内支付";
                          document.all["timer"].innerHTML = msg;
                          --maxtime;
                      } else {
                            pass = true; //判断是否过期
                          clearInterval(timer);
                      }
                  }
                  timer = setInterval("CountDown()", 1000); 
                  
                  
                  function getStatus() {
                    $.ajax({
                        method: 'GET',
                        url: './ispaid.php',
                        timeout: 10000,
                        data: {
                            'id': '<?php echo ID ?>',
                            'order_id': '<?php echo $order_id ?>',
                            'token': '<?php echo TOKEN ?>'
                        },
                        success: function(data) {
                            data = eval('(' + data + ')');
                            console.log(data);
                            if (data == '1') {
                                clearInterval(check);
                                $('#notice').html('<div class="alert alert-success" role="alert">感谢您的投食,跳转中...</div>')
                                window.location.href = 'return.php'
                            } 
                        },
                        complete: function(xhr, status) {
                            if(pass)
                            {
                                //如果超时,移除二维码
                                $('#qr').html('<h3>二维码已过期</h3>')
                                 clearInterval(check);
                            }
                        }
                    });
                }
                check = setInterval("getStatus()", 4000); 
                 </script>
    </body>
    <footer>
        <div style='Height:5px'></div>
        <center><div class="well well-sm">Copyright © 2020 - <?php echo date('Y') ?> XCSOFT All Rights Reserved.</div></center>
    </footer>