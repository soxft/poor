<?php
require_once 'config.php';

session_start();
$_SESSION['money'] = $_POST['money'];
$_SESSION['comment'] = $_POST['comment'];

if(!is_numeric($_POST['money'])){
    $datax = array('1001','扶贫金额应该是一个数字');
} elseif(empty($_POST['comment'])) {
    $datax = array('1002','给我留个言吧，别空着啊');
}elseif($_POST['money'] > 100 || $_POST['money'] < 0) {
    $datax = array('1003','扶贫资金应该在0~100之间');
} else{
    $datax = array('200','');
}

echo json_encode($datax);

//获取创建订单
$user = '扶贫';
$price = $_SESSION['money'];
$comment = $_SESSION['comment'];
$uid = mysqli_fetch_assoc(mysqli_query($conn,'select uuid() AS uid'))['uid'];
$_SESSION['uid'] = $uid;

$data = $uid . ',' . $comment;

$data = array(
    "id" => ID,//你的码支付ID
    "pay_id" => $user, //唯一标识 可以是用户ID,用户名,session_id(),订单ID,ip 付款后返回
    "type" => 1,//1支付宝支付 3微信支付 2QQ钱包
    "price" => $price,//金额100元
    "param" => $data,//自定义参数
    "notify_url"=> URL . 'noticfy.php',//通知地址
    "return_url"=> URL . 'return.php',//跳转地址
    'page' => '4',
); //构造需要传递的参数

ksort($data); //重新排序$data数组
reset($data); //内部指针指向数组中的第一个元素

$sign = ''; //初始化需要签名的字符为空
$urls = ''; //初始化URL参数为空

foreach ($data AS $key => $val) { //遍历需要传递的参数
    if ($val == ''||$key == 'sign') continue; //跳过这些不参数签名
    if ($sign != '') { //后面追加&拼接URL
        $sign .= "&";
        $urls .= "&";
    }
    $sign .= "$key=$val"; //拼接为url参数形式
    $urls .= "$key=" . urlencode($val); //拼接为url参数形式并URL编码参数值

}
$query = $urls . '&sign=' . md5($sign . KEY); //创建订单所需的参数
$url = "http://api5.xiuxiu888.com/creat_order/?{$query}"; //支付页面


$return = json_decode(file_get_contents($url),true);
$_SESSION['order_id'] = $return['order_id'];
$_SESSION['money'] = $return['money'];
$_SESSION['endTime'] = $return['endTime'];
$_SESSION['qrcode'] = $return['qrcode'];
