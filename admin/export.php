<?php
include '../safwl/common.php';
if($islogin !=1 || empty($_SESSION['swxcjebbs']) ||  $_SESSION['swxcjebbs'] != md5($_COOKIE['admin_token']."****")){
              exit("<script language='javascript'>window.location.href='./login.php';</script>");
}
$act = empty($_GET['act'])?null:$_GET['act'];
$gid = intval($_GET['id']);

if($act == "ex_sykm"){
    $gName = $_GET['gname'];
    $res = $DB->query("select * from safwl_km where gid=$gid and stat = 0");
    $data = "";
    while ($row = $DB->fetch($res)) {
        $data = $data.$row['km']. "\n";
    }
    $file_name=$gName.'_剩余卡密_'.time().'.txt';
    $file_size=strlen($data);
    header("Content-Description: File Transfer");
    header("Content-Type:application/force-download");
    header("Content-Length: {$file_size}");
    header("Content-Disposition:attachment; filename={$file_name}");
    echo $data;
}elseif($act == "ex_ysykm"){
    $gName = $_GET['gname'];
    $res = $DB->query("select * from safwl_km where gid=$gid and stat = 1");
    $data = "";
    while ($row = $DB->fetch($res)) {
        $data = $data.$row['km']. "\n";
    }
    $file_name=$gName.'_已使用卡密_'.time().'.txt';
    $file_size=strlen($data);
    header("Content-Description: File Transfer");
    header("Content-Type:application/force-download");
    header("Content-Length: {$file_size}");
    header("Content-Disposition:attachment; filename={$file_name}");
    echo $data;
}elseif($act == "ex_allkm"){
    $gName = $_GET['gname'];
    $res = $DB->query("select * from safwl_km where gid=$gid");
    $data = "";
    while ($row = $DB->fetch($res)) {
        $data = $data.$row['km']. "\n";
    }
    $file_name=$gName.'_全部卡密_'.time().'.txt';
    $file_size=strlen($data);
    header("Content-Description: File Transfer");
    header("Content-Type:application/force-download");
    header("Content-Length: {$file_size}");
    header("Content-Disposition:attachment; filename={$file_name}");
    echo $data;
}elseif($act == "coupon"){
    $res = $DB->query("select * from safwl_coupon order by id desc limit $gid");
    $data = "";
    while ($row = $DB->fetch($res)) {
        $data = $data.$row['coupon_ka']. "\n";
    }
    $file_name='优惠券_'.time().'.txt';
    $file_size=strlen($data);
    header("Content-Description: File Transfer");
    header("Content-Type:application/force-download");
    header("Content-Length: {$file_size}");
    header("Content-Disposition:attachment; filename={$file_name}");
    echo $data;
}