<?php
include 'safwl/common.php';
@header('Content-Type: application/json; charset=UTF-8');
/*
 * 源码来源：52站长论坛
 * QQ:122564034
 * 最新源码下载地址：qin52.com
 * */
if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {

} else {
    exit( "页面非法请求！");
}
if(empty($_GET['act'])){
    exit("非法访问！");
}else{
    $act=$_GET['act'];
}

switch ($act){
    //获取商品
    case "getgoods":
           $tid = intval($_POST['tid']);
           $sql = "select id,gName,gInfo,imgs,price from safwl_goods where state=1 and tpId = ".$tid ." order by sotr desc";
           $rs = $DB->query($sql);
           $goodslist = array();
           $i = 0;
           while ($row = $DB->fetch($rs)){
               $goods = array();
               $goods['id'] = $row['id'];
               $goods['name'] = $row['gName'];
               $goods['info'] = $row['gInfo'];
               $goods['imgs'] = $row['imgs'];
               $goods['price'] = $row['price'];
               $goods['kccount'] = $DB->count("select count(id) from safwl_km where stat = 0 and gid = ".$row['id']);
               array_push($goodslist, $goods);
               $i++;
           }
           $msg = array("code"=>1,"msg"=>"获取成功","goodslist"=>$goodslist,"number"=>$i);
           $DB->close();
           exit(json_encode($msg));
    break;
    case 'createorder':
        if(empty($_SESSION['createcount'])){
            $returndata = array("code"=>-1,"msg"=>"商品验证失败，error：1001");
            $DB->close();
            exit(json_encode($returndata));
        }
        if(empty($conf['epay_id'])){
            $returndata = array("code"=>-1,"msg"=>"当前站点没有配置有效的支付接口！");
            $DB->close();
            exit(json_encode($returndata));
        }
        $tradeno = _safwl($_POST['tradeno']);
        if($conf['tradenotype']==2){
            $tradeno = date("YmdHis").rand(111,999);
        }
        $gid = intval($_POST['gid']);
        $allprice = daddslashes($_POST['allprice']);
        $price = daddslashes($_POST['price']);
        $qq = daddslashes($_POST['qq']);
        $qq = _safwl($qq);
        $phone = _safwl($_POST['phone']);
		$fcwfid = _safwl($_POST['fcwfid']);
		
		$qkpb = _safwl($_POST['qkpb']);
		$jnyt = _safwl($_POST['jnyt']);
		$ffgh = _safwl($_POST['ffgh']);
        $type = daddslashes($_POST['type']);
        $type = _safwl($type);
        $number = intval($_POST['number']);
       
        if($DB->get_row("select * from safwl_blacklist where (type =2 and data = '$clientip') || (type = 1 and data = '$qq')")){
            $returndata = array("code"=>-1,"msg"=>"您已被列入云黑名单！");
                   $DB->close();
                   exit(json_encode($returndata));
        }
        
      
        $sql = "select price_way from safwl_goods where id = ".$gid;
        $row = $DB->get_row($sql);
        if($conf['paypasstype']==1){
             $paypass = _safwl($_POST['paypass']);
             if($paypass==""){
                 $returndata = array("code"=>-1,"msg"=>"为了您的卡密安全,请设置您的提取密码！");
                   $DB->close();
                   exit(json_encode($returndata));
             }
             if($paypass != $_POST['paypass']){
                    $returndata = array("code"=>-1,"msg"=>"提取密码只能使用英文数字！");
                   $DB->close();
                   exit(json_encode($returndata));
               }
        }else{
            $paypass = "";
        }
        if($row['price_way'] != $price){
            $returndata = array("code"=>-1,"msg"=>"商品验证失败");
            $DB->close();
            exit(json_encode($returndata));
        }
        if($type != "alipay" && $type != "wxpay"  && $type != "qqpay"  && $type != "tenpay" ){
             $returndata = array("code"=>-1,"msg"=>"支付方式".$type);
             $DB->close();
             exit(json_encode($returndata));
        }
        
        $checkallprice = round($price*$number,2);
        if($allprice != $checkallprice){
             $returndata = array("code"=>-1,"msg"=>"商品验证失败");
             $DB->close();
             exit(json_encode($returndata));
        }
        $checkdd = $DB->get_row("select out_trade_no from safwl_order where out_trade_no = '$tradeno'");
        if($checkdd){
             $returndata = array("code"=>-1,"msg"=>"请勿重复创建订单！");
             $DB->close();
             exit(json_encode($returndata));
        }
         $ap = $allprice;
         $psm = "";
         $coupon_id = 0;
        if($conf['iscoupon']==1){
              $coupon = _safwl($_POST['coupon']);//优惠券
              if($coupon){
                  $sql = "select * from safwl_coupon where coupon_ka = '$coupon' limit 1";
                  $row = $DB->get_row($sql);
                  if($row){
                      if($row['coupon_status'] != 0){
                           $returndata = array("code"=>-1,"msg"=>"优惠券已被使用！");
                           $DB->close();
                           exit(json_encode($returndata));
                      }
                      if($row['coupon_endtime'] < $date){
                           $returndata = array("code"=>-1,"msg"=>"优惠券已过期！");
                           $DB->close();
                           exit(json_encode($returndata));
                      }
                      if($row['coupon_goods_id'] != 0 && $row['coupon_goods_id'] != $gid){
                          $returndata = array("code"=>-1,"msg"=>"当前商品无法使用该优惠券！");
                           $DB->close();
                           exit(json_encode($returndata));
                      }
                     $coupon_id = $row['id'];
                      if($row['coupon_type'] == 1){
                          //代金券
                          $value = $row['coupon_value'];
                          $psm = "已优惠：".$value."元";
                          $allprice = round($allprice - $value,2);
                      }
                      if($row['coupon_type'] == 2){
                          //折扣券
                           $value = $row['coupon_value'];
                           $psm = "已折扣：".$value."折";
                           $allprice = round($allprice * $value*0.1,2);
                      }
                      if($allprice < 0 || $allprice == $ap){
                           $returndata = array("code"=>-1,"msg"=>"当前商品价格不能再使用优惠券了！");
                        $DB->close();
                        exit(json_encode($returndata));
                      }
                  }else{
                      $returndata = array("code"=>-1,"msg"=>"请输入的优惠券不存在！");
                        $DB->close();
                        exit(json_encode($returndata));
                  }
              }
        }
        if($conf['sendphonedx'] && $phone != ""){
            if(!preg_match("/^1[345678]{1}\d{9}$/",$phone)){
                  $returndata = array("code"=>-1,"msg"=>"请输入正确的手机号码！");
                $DB->close();
                exit(json_encode($returndata));
            }
            $allprice = $allprice+0.1;
            if($psm!=""){ $psm .= "/增值服务0.1元-$phone";}else{$psm .= "增值服务0.1元-$phone";}
        }
        if(!empty($fcwfid)){
		$md5_tradeno = md5($tradeno.$allprice);
        $sql = "insert into safwl_order(out_trade_no,md5_trade_no,gid,paypass,money,rel,type,benTime,number,coupon_id,phone,fcwfid,sta,sendE) "
                . "values('$tradeno','$md5_tradeno',$gid,'$paypass','$allprice','$qq','$type',now(),$number,$coupon_id,'$phone',$fcwfid,0,0)";
		}else{
		if(!empty($qkpb)){
	    $md5_tradeno = md5($tradeno.$allprice);
        $sql = "insert into safwl_order(out_trade_no,md5_trade_no,gid,paypass,money,rel,type,benTime,number,coupon_id,phone,qkpb,jnyt,ffgh,sta,sendE) "
                . "values('$tradeno','$md5_tradeno',$gid,'$paypass','$allprice','$qq','$type',now(),$number,$coupon_id,'$phone','$qkpb','$jnyt','$ffgh',0,0)";
		}else{
		$md5_tradeno = md5($tradeno.$allprice);
        $sql = "insert into safwl_order(out_trade_no,md5_trade_no,gid,paypass,money,rel,type,benTime,number,coupon_id,phone,sta,sendE) "
                . "values('$tradeno','$md5_tradeno',$gid,'$paypass','$allprice','$qq','$type',now(),$number,$coupon_id,'$phone',0,0)";
			
		}
		}
		
        if($DB->query($sql)){
           
            wsyslog("创建订单成功!","IP:".real_ip().",当前IP此次创建订单数量：".$_SESSION['createcount']."");
          
            $returndata = array("code"=>1,"msg"=>"订单创建成功","md5_tradeno" =>$md5_tradeno,"tradeno"=>$tradeno,"origprice"=>$ap,"allprice"=>$allprice,"psm"=>$psm);
        }else{
            $returndata = array("code"=>-1,"msg"=>"订单创建失败");
        }
          $_SESSION['createcount']++;
        $DB->close();
        exit(json_encode($returndata));
        break;
       
    default :exit('{"code":-1,"msg":"哦豁？"}');
        
}


?>