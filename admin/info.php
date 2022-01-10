<?php
$title='其它管理';
include './head.php';

if(!empty($_GET['act']) && $_GET['act'] != ""){
    $act = $_GET['act'];
}else{
    $act = 1;
}

?>
<div class="container" style="padding-top:70px;">
    <div class="col-xs-12 col-sm-10 col-lg-8 center-block" style="float: none;">
    <?php 
        if($act == "jkinfo"){
    ?>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">订单监控地址</h3>
            </div>
            <div class="panel-body">
                <center>
                    <h4>自动发送邮件：<?php echo "http://".$_SERVER['HTTP_HOST']."/safwl/mon.php";?></h4>
                    <h4>自动补单：<?php echo "http://".$_SERVER['HTTP_HOST']."/safwl/auto.php";?></h4>
                    <h4>如不准确请以真实地址为准！</h4>
                    <hr>
                    <a href="https://www.aliyun.com/" target="_balck">点击加入阿里云监控</a><br><br>
                    <a href="http://www.qin52.com" target="_balck">点击下载本地监控软件</a>
                </center>
            </div>
        </div>
    <?php 
        }elseif($act == "updateinfo"){
    ?>
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title"  style="text-align: center;">版本更新</h3>
            </div>
            <div class="list-group-item list-group-item-success"  style="text-align: center;">
                <div>ssss
                    <h4>当前版本：<?php echo VERSION;?></h4> <br>
                    <iframe src="../readme.txt" style="border: 0px;min-height: 600px;min-width: 100%">
                    </iframe>
                </div>
            </div>
        </div>
    <?php
        }elseif($act == "updatesub"){
    ?>
        <div class="list-group-item list-group-item-success"  style="text-align: center;">
    <?php 
        echo '程序已是最新版本！<br>';
        echo '<a href="./">返回首页</a>';
    ?>
        </div>
    <?php 
        }
    ?>
    </div>
</div>
<!-- 
**********************************************
/*
 * 源码来源：52站长论坛
 * QQ:122564034
 * 最新源码下载地址：qin52.com
 * */ 
**********************************************
-->