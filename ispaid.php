<?php
require_once 'config.php';
session_start();

$uid = $_SESSION['uid'];
$sql = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM `order` WHERE uid='$uid'"));
if(!empty($sql['time']))
{
    echo '1';
    session_destroy();
} else {
    echo '0';
}