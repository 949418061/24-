<?php
header("Content-Type: text/html; charset=utf-8");
include 'common.php';
if (function_exists("set_time_limit"))
{
    @set_time_limit(0);
}
if (function_exists("ignore_user_abort"))
{
    @ignore_user_abort(true);
}

if($conf['epay_id'] && $conf['epay_key']){
    
    
   $data = get_curl($payapi.'api.php?act=orders&limit=30&&pid='.$conf['epay_id'].'&key='.$conf['epay_key'].'&url='.$_SERVER['HTTP_HOST']);
    $arr = json_decode($data, true);

    if($arr['code']==1){
        foreach($arr['data'] as $row){
            if($row['status']==1){
                $out_trade_no = $row['out_trade_no'];
                $trade_no = $row['trade_no'];
                $row = $DB->get_row("select * from safwl_order where out_trade_no = '{$out_trade_no}' and sta = 0");
                if($row && $row['id'] >0){
                   
                    $crow = $DB->get_row("select * from safwl_km where gid = {$row['gid']} and stat = 0");
                    if($crow && $crow['id'] >0){
                       $number = $crow['number'];
                        $DB->query("update safwl_order set trade_no='{$trade_no}',endTime=now(),sta = 1 where id = ".$row['id']);
                        $DB->query("update safwl_km set trade_no='{$trade_no}',endTime=now(),stat = 1,out_trade_no = '{$out_trade_no}',rel = '{$row['rel']}' where gid = {$row['gid']} and stat = 0  limit  $number");
                        echo $out_trade_no."补单成功!<br>";
                    }else{
                        echo $out_trade_no."补单失败,卡密不足!<br>";
                    }
                }
            }
        }
        $DB->close();
        exit('ok');
    }else{
        exit("api:".$arr['msg']);
    }
}else{
    exit('请先配置支付接口账号');
}
