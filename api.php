<?php
session_start();
require_once 'config.php';

if (!is_numeric($_POST['money'])) {
    $datax = array('1001','扶贫金额应该是一个数字');
} elseif (empty($_POST['comment'])) {
    $datax = array('1002','给我留个言吧，别空着啊');
} elseif ($_POST['money'] > 100 || $_POST['money'] < 0) {
    $datax = array('1003','扶贫资金应该在0~100之间');
} else {
    $datax = array('200','');
    $outTradeNo = uniqid();
    //你自己的商品订单号
    $payAmount = $_POST['money'];
    //付款金额，单位:元
    $orderName = '扶贫';
    //订单标题
    $signType = 'RSA2';
    //签名算法类型，支持RSA2和RSA，推荐使用RSA2
    //商户私钥，填写对应签名算法类型的私钥，如何生成密钥参考：https://docs.open.alipay.com/291/105971和https://docs.open.alipay.com/200/105310
    $aliPay = new AlipayService($appid,$returnUrl,$notifyUrl,$saPrivateKey);
    $result = $aliPay->doPay($payAmount,$outTradeNo,$orderName,$returnUrl,$notifyUrl);
    $result = $result['alipay_trade_precreate_response'];
    if ($result['code'] && $result['code'] == '10000') {
        //生成二维码
        $time = time();
        $comment = $_POST['comment'];
        mysqli_query($conn,"INSERT INTO `content` VALUES('$outTradeNo','false','$payAmount','$comment','$time')");
        $_SESSION['qrcode'] = $result['qr_code'];
        $_SESSION['endTime'] = time() + 5*60; //超时时间
        $_SESSION['money'] = $payAmount;
        $_SESSION['outTradeNo'] = $outTradeNo;
        $datax = array('200','');
    } else {
        $datax = array('1004','支付宝网关请求失败,请稍后再试');
    }
}
echo json_encode($datax);





class AlipayService
{
    protected $appId;
    protected $returnUrl;
    protected $notifyUrl;
    //私钥文件路径
    protected $rsaPrivateKeyFilePath;
    //私钥值
    protected $rsaPrivateKey;
    public function __construct($appid, $returnUrl, $notifyUrl,$saPrivateKey) {
        $this->appId = $appid;
        $this->returnUrl = $returnUrl;
        $this->notifyUrl = $notifyUrl;
        $this->charset = 'utf8';
        $this->rsaPrivateKey = $saPrivateKey;
    }

    /**
     * 发起订单
     * @param float $totalFee 收款总费用 单位元
     * @param string $outTradeNo 唯一的订单号
     * @param string $orderName 订单名称
     * @param string $notifyUrl 支付结果通知url 不要有问号
     * @param string $timestamp 订单发起时间
     * @return array
     */
    public function doPay($totalFee, $outTradeNo, $orderName, $returnUrl,$notifyUrl) {
        //请求参数
        $requestConfigs = array(
            'out_trade_no' => $outTradeNo,
            'total_amount' => $totalFee, //单位 元
            'subject' => $orderName,  //订单标题
            'timeout_express' => '5m'       //该笔订单允许的最晚付款时间，逾期将关闭交易。取值范围：1m～15d。m-分钟，h-小时，d-天，1c-当天（1c-当天的情况下，无论交易何时创建，都在0点关闭）。 该参数数值不接受小数点， 如 1.5h，可转换为 90m。
        );
        $commonConfigs = array(
            //公共参数
            'app_id' => $this->appId,
            'method' => 'alipay.trade.precreate',             //接口名称
            'format' => 'JSON',
            'charset' => $this->charset,
            'sign_type' => 'RSA2',
            'timestamp' => date('Y-m-d H:i:s'),
            'version' => '1.0',
            'notify_url' => $notifyUrl,
            'biz_content' => json_encode($requestConfigs),
        );
        $commonConfigs["sign"] = $this->generateSign($commonConfigs, $commonConfigs['sign_type']);
        $result = $this->curlPost('https://openapi.alipay.com/gateway.do',$commonConfigs);
        return json_decode($result,true);
    }
    public function generateSign($params, $signType = "RSA") {
        return $this->sign($this->getSignContent($params), $signType);
    }
    protected function sign($data, $signType = "RSA") {
        $priKey = $this->rsaPrivateKey;
        $res = "-----BEGIN RSA PRIVATE KEY-----\n" .
        wordwrap($priKey, 64, "\n", true) .
        "\n-----END RSA PRIVATE KEY-----";
        ($res) or die('您使用的私钥格式错误，请检查RSA私钥配置');
        if ("RSA2" == $signType) {
            openssl_sign($data, $sign, $res, version_compare(PHP_VERSION,'5.4.0', '<') ? SHA256 : OPENSSL_ALGO_SHA256);
            //OPENSSL_ALGO_SHA256是php5.4.8以上版本才支持
        } else {
            openssl_sign($data, $sign, $res);
        }
        $sign = base64_encode($sign);
        return $sign;
    }
    /**
     * 校验$value是否非空
     *  if not set ,return true;
     *    if is null , return true;
     **/
    protected function checkEmpty($value) {
        if (!isset($value))
            return true;
        if ($value === null)
            return true;
        if (trim($value) === "")
            return true;
        return false;
    }
    public function getSignContent($params) {
        ksort($params);
        $stringToBeSigned = "";
        $i = 0;
        foreach ($params as $k => $v) {
            if (false === $this->checkEmpty($v) && "@" != substr($v, 0, 1)) {
                // 转换成目标字符集
                $v = $this->characet($v, $this->charset);
                if ($i == 0) {
                    $stringToBeSigned .= "$k" . "=" . "$v";
                } else {
                    $stringToBeSigned .= "&" . "$k" . "=" . "$v";
                }
                $i++;
            }
        }
        unset ($k, $v);
        return $stringToBeSigned;
    }

    /**
     * 转换字符集编码
     * @param $data
     * @param $targetCharset
     * @return string
     */
    function characet($data, $targetCharset) {
        if (!empty($data)) {
            $fileType = $this->charset;
            if (strcasecmp($fileType, $targetCharset) != 0) {
                $data = mb_convert_encoding($data, $targetCharset, $fileType);
                //$data = iconv($fileType, $targetCharset.'//IGNORE', $data);
            }
        }
        return $data;
    }


    public function curlPost($url = '', $postData = '', $options = array()) {
        if (is_array($postData)) {
            $postData = http_build_query($postData);
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        //设置cURL允许执行的最长秒数
        if (!empty($options)) {
            curl_setopt_array($ch, $options);
        }
        //https请求 不验证证书和host
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
}