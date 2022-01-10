<?php
/*
 * 源码来源：52站长论坛
 * QQ:122564034
 * 最新源码下载地址：qin52.com
 * */  

    include '../safwl/common.php';
    @header('Content-Type: text/html; charset=UTF-8');
    if($islogin==1){
        if( empty($_SESSION['swxcjebbs']) ||  $_SESSION['swxcjebbs'] != md5($_COOKIE['admin_token']."****")){
              exit("<script language='javascript'>window.location.href='./login.php';</script>");
        }
		$r3 =$DB->count("select COUNT(id) from safwl_km");
?>

<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8"/>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <title><?=$title?> - 后台管理中心</title>
	<link rel="stylesheet" type="text/css" href="../assets/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="../assets/css/app.css" />
    <link rel="stylesheet" href="../assets/css/c/layui.css" media="all">
    <link rel="stylesheet" href="../assets/css/c/admin.css" media="all">
    <script type="text/javascript" src="../assets/jquery/jquery.min.js"></script>
    <script type="text/javascript" src="../assets/jquery/jquery.cookie.js"></script>
    <script type="text/javascript" src="../assets/jquery/jquery.md5.js"></script>
    <script type="text/javascript" src="../assets/js/sweetalert.min.js"></script>
    <script type="text/javascript" src="../assets/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="../assets/layer/layer.js"></script>
    <script type="text/javascript" src="../assets/js/safwl.js"></script>
	
	
	
    <!--[if lt IE 9]>
        <script src="//cdn.bootcss.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    
  
</head>

<?php 
    }else{
        exit("<script language='javascript'>window.location.href='./login.php';</script>");
    }  
?>
<!-- 
**********************************************
          /*
 * 源码来源：52站长论坛
 * QQ:122564034
 * 最新源码下载地址：qin52.com
 * */         ~~      
**********************************************
-->