<?php
require_once 'config.php';
session_start();

$qrcode = $_SESSION['qrcode'];
$money = $_SESSION['money'];
$endTime = $_SESSION['endTime'];
$outTradeNo = $_SESSION['outTradeNo'];

if(empty($endTime)){
    header('Refresh:0;url=\'./index.php\'');
    exit();
}
if($endTime <= time())
{
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
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/soxft/cdn@master/mdui/css/mdui.min.css">
        <script src="https://cdn.jsdelivr.net/gh/soxft/cdn@master/mdui/js/mdui.min.js"></script>
        <script type="text/javascript" src="//cdn.staticfile.org/jquery/2.1.1/jquery.min.js"></script>
        <script type="text/javascript" src="//static.runoob.com/assets/qrcode/qrcode.min.js"></script>
    </head>
    <body background="//static.llilii.cn/images/kagamine/32639516_p2.jpg">
        <div style="Height:60px"></div>
        <div class="mdui-container" style="max-width: 360px;">
            <div class="mdui-card" style="border-radius: 16px;">
                <div class="mdui-card-menu">
                    <button onclick="window.location.href='/'" class="mdui-btn mdui-btn-icon mdui-text-color-grey"><i class="mdui-icon material-icons">home</i>
                    </button>
                </div>
                <div class="mdui-card-primary">
                    <div class="mdui-card-primary-title">支付宝扫码支付
                        <?php echo $money ?>元</div>
                    <div class="mdui-card-primary-subtitle">Use Alipay to pay
                        <?php echo $money ?> CNY</div>
                </div>
                <div id='cont' class="mdui-card-content">
                    <!-- 加载 -->
                    <center>
                        <div id='qr'>
                            <a href='<?php echo $qrcode ?>'><div id="qrcode"></div></a>
                        </div>
                         <h4 id='timer'>请在null分null秒内支付</h4>
                         <h4>支付完成后请耐心等待5秒</h4>
                         <h5>订单号: <?php echo $outTradeNo ?></h5>
                    </center>
                </div>
                <div style='Height:30px'></div>
            </div>
        </div>
         <div style='Height:20px'></div>
 <div class="mdui-container" style="max-width: 360px;">
    <div class="mdui-card" style="border-radius: 16px;">
        <div class="mdui-card-content">
        <center>Copyright © 2020 - <?php echo date('Y') ?> <a style='color:black' href='//xsot.cn'>xcsoft</a> All Rights Reserved.</center>
        </div>
    </div>
</div>
 <div style='Height:20px'></div><div style='Height:30px'></div>
                <script>
                    new QRCode(document.getElementById("qrcode"), 
                    {
                        text: "<?php echo $qrcode ?>",
                        width: 170,
                        height: 170,
                        colorDark : "#000000",
                        colorLight : "#ffffff",
                        correctLevel : QRCode.CorrectLevel.H
                    });  // 设置要生成二维码的链接
                var $ = mdui.JQ;
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
                        url: './getPay.php',
                        timeout: 10000,
                        data: {
                            'outTradeNo': '<?php echo $outTradeNo ?>',
                        },
                        success: function(data) {
                            if(data == 'true'&&!pass){
                                pass = true;
                                clearInterval(g) 
                                $('#cont').html('<center><div style="font-weight:100;font-size:30px">感谢您的扶贫</div></center>')
                                setTimeout('window.location.href=\'index.php\'',3000)
                            }else{
                                console.log(data);
                            }
                        }
                    });
                }
                var g = setInterval("getStatus()", 3000); 
    </script>
                
                 </script>
    </body>