<?php
/*
 * 源码来源：52站长论坛
 * QQ:122564034
 * 最新源码下载地址：qin52.com
 * */  
//监控订单
require './common.php';
$trade_no = $_POST['trade_no'];
$gid = $_POST['gid'];
$out_trade_no = $_POST['out_trade_no'];
$ke = $_POST['ke'];
$kaa = $_POST['kaa'];
$count=1; 
$interval=50; 
$today=date("Y-m-d ").'00:00:00';
$nowdate = date("Y-m-d H:i:s");
if($conf['sendemail']==0){
exit("<script>alert('发送失败邮箱功能已关闭!');location.href='".$_SERVER["HTTP_REFERER"]."';</script>");
}
if (function_exists("set_time_limit"))
{
	@set_time_limit(0);
}
if (function_exists("ignore_user_abort"))
{
	@ignore_user_abort(true);
}
//
$rs=$DB->query("SELECT * FROM safwl_order WHERE sta = 1 and sendE = 0 and LENGTH(rel)>=5 order by id desc  limit $count");
while($row = $DB->fetch($rs))
{
    
    //$rr = $DB->get_row("select * from safwl_km where trade_no = '".$row['trade_no']."' limit 1");

    $km = "";
    $res2 = $DB->query("select * from safwl_km where trade_no = '".$trade_no."' ");
    while ($rr = $DB->fetch($res2)){
        if($km != ""){
            $km .= ",";
        }
        $km .= $rr['km'];
    }
    $rw = $DB->get_row("select * from safwl_goods where id = '".$gid."' limit 1");
   
    $bh = $out_trade_no;//订单编号
    $mc = $rw['gName'];//名称
    $time = $row['endTime'];//时间
    $goal =  $row['rel']."@qq.com";//目标邮箱
    $content = "<br>　　您购买的商品：".$mc."<br>　　订单编号：".$bh."<br>　　购买时间为：".$time."<br>　　快递公司：".$ke."<br>　　快递单号：".$kaa;
    sendemail($goal,getMd_df(get_qqnick($row['rel']),$content,$conf['title'],""));
	$DB->query("update safwl_order set sendE = 1 where id='{$row['id']}'");
	@usleep($interval*1000);
}
 echo "<script>alert('已经提交');location.href='".$_SERVER["HTTP_REFERER"]."';</script>"; 