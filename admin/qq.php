<?php

  include '../safwl/common.php';
    @header('Content-Type: text/html; charset=UTF-8');
    if($islogin==1){
        if( empty($_SESSION['swxcjebbs']) ||  $_SESSION['swxcjebbs'] != md5($_COOKIE['admin_token']."****")){
              exit("<script language='javascript'>window.location.href='./login.php';</script>");
        }
     }else{
        exit("<script language='javascript'>window.location.href='./login.php';</script>");
    } 
?>