<?php
/**
 * 系统运行日志
**/
$title='系统运行日志 ';
include './head.php';
?>
  <div class="container" style="padding-top:70px;">
    <div class="col-sm-12 col-md-10 center-block" style="float: none;">
<?php 
	$numrows=$DB->count("SELECT count(*) from safwl_syslog");
	
	$sql=" 1";
	$con='系统共有 <b>'.$numrows.'</b>条运行日志。<a onclick="detlog()" style="color:red;">全部清空</a>';
    echo $con;
?>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead><tr><th>ID</th><th>名称类型</th><th>运行时间</th><th>详细日志</th></tr></thead>
                <tbody>
                <?php
                    $fyzy = "";
                    $pagesize=30;
                    $pages=intval($numrows/$pagesize);
                    if ($numrows%$pagesize){
                        $pages++;
                    }
                    if (isset($_GET['page'])){
                        $page=intval($_GET['page']);
                    }else{
                        $page=1;
                    }
                    $offset=$pagesize*($page - 1);
                    $rs=$DB->query("SELECT * FROM safwl_syslog WHERE{$sql} order by id desc limit $offset,$pagesize");
                    $fyzy = "?page=";
                    while($res = $DB->fetch($rs)){
                        echo '<tr><td>'.$res['id'].'</td><td>'.$res['log_name'].'</td><td>'.$res['log_time'].'</td><td>'.htmlspecialchars($res['log_txt']).'</td></tr>';
                    }
                ?>
                </tbody>
            </table>
        </div>
        <?php include "fy.php" ?>
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