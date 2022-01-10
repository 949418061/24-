<?php

define('SYSTEM_ROOT_E', dirname(__FILE__) . '/');
include './qq.php';
   
ksort($_POST); //排序post参数
reset($_POST); //内部指针指向数组中的第一个元素

 //合法的数据
    $trade_no = date ( "Ymdhis").number_format(microtime(true),4,'','');
	
    $out_trade_no = $_GET['out_trade_no'];
    $sql = "SELECT * FROM safwl_order WHERE out_trade_no='{$out_trade_no}' limit 1";
    $res = $DB->query($sql);
    $srow = $DB->fetch($res);
  /*  if($srow['sta']==1){
        exit('success');
    }
    $sql = "update safwl_order set sta = 1, trade_no = '{$trade_no}' ,endTime = now() where out_trade_no = '{$out_trade_no}'";
    $sql2 = "UPDATE safwl_km set endTime = now(),out_trade_no = '{$out_trade_no}',trade_no='{$trade_no}',rel ='{$srow['rel']}',stat = 1
        where gid = {$srow['gid']} and stat = 0
        limit  1";
    $DB->query($sql);
    $DB->query($sql2);*/
//exit($srow['sta']);
     if($srow['sta']==0){
           /* if(!srow || $srow['money'] != $_GET['money']){
                showalert('验证失败！',4,'订单回调验证失败！');
            }*/
            $number = $srow['number'];
            $ok = 0;
            for($i=1;$i<=$number;$i++){
                $sql2 = "UPDATE safwl_km "
                        . "set endTime = now(),out_trade_no = '{$out_trade_no}',trade_no='{$trade_no}',rel ='{$srow['rel']}',stat = 1
                           where gid = {$srow['gid']} and stat = 0
                           limit  1";
                if($DB->query($sql2)){
                    $ok++; 
                }
            }
            $sql = "update safwl_order set sta = 1, trade_no = '{$trade_no}' ,endTime = now() where out_trade_no = '{$out_trade_no}'";
                  
            $DB->query($sql);
            wsyslog("交易成功！[M-异步处理]","订单编号：".$out_trade_no.";数量：".$number.";成功提取数量：".$ok."");
          
          
        }
   echo "<script>alert('操作成功!');location.href='".$_SERVER["HTTP_REFERER"]."';</script>"; 

?>