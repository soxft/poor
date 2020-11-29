<?php
require_once 'config.php';

$data = file_get_contents('https://api.xiuxiu888.com/ispay?id='.ID.'&token=' .TOKEN .'&order_id='.$_GET['order_id']);

$data = json_decode($data,true);

echo $data['status'];